<template>
    <li>
        <router-link :to="`/users/${user.id}`" class="thumbnaillink">
            <img
                :src="user.userthumbnail.url"
                :alt="`${user.name}のサムネイル`"
                class="thumbnail"
            />
        </router-link>
        <div class="userInformation">
            <router-link :to="`/users/${user.id}`"
                ><h1>{{ user.name }}</h1></router-link
            >
            <p>{{ user.introduction }}</p>
            <div v-if="isLogin && isMyacount(user.id)">
                <button
                    @click="follow(user.id)"
                    v-show="!followees.includes(user.id)"
                >
                    フォローする
                </button>
                <button
                    @click="unfollow(user.id)"
                    v-show="followees.includes(user.id)"
                    class="unfollow"
                >
                    フォロー解除
                </button>
            </div>
        </div>
        <div class="productsList">
            <Product
                v-for="product in products"
                :key="product.id"
                :product="product"
                :productstyle="productStyle"
            />
        </div>
    </li>
</template>

<script>
import Product from "../components/Products/Product.vue";
import Axios from "axios";
import { OK } from "../util";
export default {
    components: {
        Product
    },
    props: {
        index: Number,
        user: {}
    },
    data() {
        return {
            maxwidth: 400,
            notification: {
                message: String,
                id: Number
            }
        };
    },
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        followees() {
            const followees = this.$store.getters["auth/followees"];
            const followeesId = [];
            for (let i = 0; i < followees.length; i++) {
                followeesId.push(followees[i].followee_id);
            }
            return followeesId;
        },
        isMyacount() {
            return function(val) {
                const myacountId = this.$store.getters["auth/userid"];
                if (val == myacountId) {
                    return false;
                } else {
                    return true;
                }
            };
        },
        authName() {
            return this.$store.getters["auth/username"];
        },
        productStyle() {
            const product = `${this.maxwidth / 3}px`;
            return {
                width: product,
                height: product
            };
        },
        products() {
            return this.user.products.slice(0, 3);
        }
    },
    methods: {
        async follow(val) {
            const response = await axios.put(`/api/user/${val}/follow`);
            await this.$store.dispatch("auth/currentUser");
            this.followedNotification();
        },
        async unfollow(val) {
            const response = await axios.delete(`/api/user/${val}/follow`);
            await this.$store.dispatch("auth/currentUser");
        },
        async followedNotification() {
            this.notification.id = this.user.id;
            this.notification.message = `${this.authName}さんが${this.user.name}さんをフォローしました。`;
            const responsse = await axios.post(
                "/api/notification",
                this.notification
            );
        }
    },
    watch: {}
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
li {
    width: 810px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    &:first-child {
        margin-top: 0;
    }
}
button {
    margin-top: 10px;
    transition-duration: 0.3s;
    border: solid 1px $maincolor;
    padding: 5px 15px;
    border-radius: 40px;
    &:hover {
        transition-duration: 0.3s;
        background-color: $maincolor;
        color: white;
    }
}
.thumbnaillink {
    width: 130px;
    height: 130px;
}
.userInformation {
    margin-left: 3%;
    width: 230px;
    display: flex;
    flex-flow: column;
    padding: 10px 0;
    align-items: flex-start;
}
.productsList {
    width: 400px;
    display: flex;
    flex-flow: row wrap;
    align-items: center;
}
.unfollow {
    border: solid 1px rgba($maincolor, 0.5);
    color: rgba($maincolor, 0.5);
    background-color: rgba($maincolor, 0.2);
    &:hover {
        background-color: rgba($maincolor, 0.2);
        color: rgba($maincolor, 0.5);
    }
}
</style>
