<template>
    <div class="userDetail">
        <div class="userInformation">
            <img
                :src="user.thumbnail"
                :alt="`${user.name}のサムネイル`"
                class="thumbnail infomationItem"
            />
            <div class="infomationItem">
                <h2 class="username">{{ user.name }}</h2>
                <p class="userintroduction">{{ user.introduction }}</p>
            </div>
            <div class="infomationItem">
                <router-link :to="`/follow/${id}`">
                    フォロー
                    <span>{{ user.follow.length }}</span>
                </router-link>
                <router-link :to="`/follower/${id}`">
                    フォロワー
                    <span>{{ user.follower.length }}</span>
                </router-link>
            </div>
            <router-link
                :to="`/settings/${id}`"
                class="infomationItem"
                v-if="id == userid"
            >
                <i class="fas fa-cog"></i>
            </router-link>
        </div>

        <ul class="tab">
            <li @click="tab = 1">
                <p
                    :class="{
                        'tab_item-active': tab === 1,
                        'tab_item-nonactive': tab !== 1
                    }"
                >
                    投稿作品
                </p>
            </li>
            <li @click="tab = 2">
                <p
                    :class="{
                        'tab_item-active': tab === 2,
                        'tab_item-nonactive': tab !== 2
                    }"
                >
                    お気に入り
                </p>
            </li>
        </ul>
        <div class="productsList" :style="style">
            <Product
                v-for="product in products"
                :key="product.id"
                :product="product"
                :appearLike="true"
                @like="onLikeClick"
                :productstyle="productStyle"
            />
        </div>
        <Pagination :current-page="currentPage" :last-page="lastPage" />
    </div>
</template>

<script>
import Pagination from "../components/Pagination.vue";
import Product from "../components/Products/Product.vue";
import Axios from "axios";
import { OK, CREATED, UNPROCESSABLE_ENTITY } from "../util";
export default {
    components: {
        Product,
        Pagination
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            currentPage: 0,
            lastPage: 0,
            maxwidth: 900,
            products: [],
            style: {
                width: "900px",
                height: "1500px"
            },
            tab: 1,
            user: {
                follow: [],
                follower: [],
                introduction: String,
                name: String,
                thumbnail: String
            }
        };
    },
    computed: {
        productStyle() {
            const product = `${this.maxwidth / 3}px`;
            return {
                width: product,
                height: product
            };
        },
        userid() {
            return this.$store.getters["auth/userid"];
        }
    },
    watch: {
        $route: {
            async handler() {
                this.$store.commit("randing/loadingSwitch", true);
                await this.showUser();
                await this.showProducts();
                this.$store.commit("randing/loadingSwitch", false);
            },
            immediate: true
        },
        tab: function(val) {
            if (val == 1) {
                this.showProducts();
            } else {
                this.showLikeProducts();
            }
        }
    },
    methods: {
        onLikeClick({ id, liked }) {
            if (!this.$store.getters["auth/check"]) {
                alert("いいね機能を使うにはログインしてください。");
                return false;
            }

            if (liked) {
                this.unlike(id);
            } else {
                this.like(id);
            }
        },
        async showProducts() {
            this.products.length = 0;
            const response = await axios.get(
                `/api/users/products/${this.id}/?page=${this.page}`
            );
            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }
            this.products = response.data.data;
            this.currentPage = response.data.current_page;
            this.lastPage = response.data.last_page;
        },
        async showLikeProducts() {
            this.products.length = 0;
            const response = await axios.get(
                `/api/users/likeproducts/${this.id}/?page=${this.page}`
            );
            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }
            this.products = response.data.data;
            this.currentPage = response.data.current_page;
            this.lastPage = response.data.last_page;
        },
        async showUser() {
            const response = await axios.get(`/api/users/${this.id}`);
            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }
            this.user.name = response.data[0].name;
            this.user.introduction = response.data[0].introduction;
            this.user.thumbnail = response.data[0].userthumbnail.url;
            this.user.follow = response.data[0].follows;
            this.user.follower = response.data[0].followers;
        },
        async like(id) {
            const response = await axios.put(`/api/products/${id}/like`);

            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }

            this.products = this.products.map(product => {
                if (product.id == response.data.product_id) {
                    product.likes_count += 1;
                    product.liked_by_user = true;
                }
                return product;
            });
        },
        async unlike(id) {
            const response = await axios.delete(`/api/products/${id}/like`);

            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }

            this.products = this.products.map(product => {
                if (product.id == response.data.product_id) {
                    product.likes_count -= 1;
                    product.liked_by_user = false;
                }
                return product;
            });
        }
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
.userDetail {
    margin: 0 auto;
    margin-top: 0;
    display: flex;
    flex-flow: column;
    width: 100%;
    align-items: center;
}
.userInformation {
    width: 850px;
    padding-top: 30px;
    display: flex;
    div {
        margin-left: 20px;
    }
}
.infomationItem:last-child {
    margin-left: auto;
}
h2 {
    margin-bottom: 10px;
}
.productsList {
    display: flex;
    flex-flow: row wrap;
    align-content: flex-start;
}
.thumbnail {
    width: 130px;
    height: 130px;
}
.tab {
    width: 25%;
    height: 85px;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    li {
        font-size: 18px;
        width: 50%;
        height: 65%;
        display: flex;
        justify-content: center;
        align-items: center;
        p {
            display: inline-block;
            margin: 0;
        }
    }
}
.tab_item-nonactive {
    transition-duration: 0.4s;
    border-bottom: solid 1.2px rgba($maincolor, 0);
}
.tab_item-active {
    transition-duration: 0.4s;
    border-bottom: solid 1.2px $maincolor;
}
</style>
