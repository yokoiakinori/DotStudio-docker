<template>
    <div class="Home">
        <div class="productsList" :style="style">
            <Product
                v-for="product in productList"
                :key="product.id"
                :product="product"
                :appearLike="true"
                @like="onLikeClick"
                :productstyle="productStyle"
            />
        </div>
        <Pagination
            :current-page="currentPage"
            :last-page="lastPage"
            :routerPath="routerPath"
        />
    </div>
</template>

<script>
import Pagination from "./Pagination.vue";
import Product from "./Products/Product.vue";
import Axios from "axios";
import { OK } from "../util";
import notification from "../mixin/notification";
export default {
    mixins: [notification],
    components: {
        Product,
        Pagination
    },
    props: {
        page: {
            type: Number,
            required: false,
            default: 1
        },
        routerPath: String,
        products: null,
        currentPage: 0,
        lastPage: 0
    },
    data() {
        return {
            maxwidth: 900,
            style: {
                width: "900px",
                height: "1500px"
            },
            productList: Array
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
        authName() {
            return this.$store.getters["auth/username"];
        }
    },
    watch: {
        products(val) {
            this.productList = val;
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
        async like(id) {
            const response = await axios.put(`/api/products/${id}/like`);

            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }

            this.productList = this.productList.map(product => {
                if (product.id == response.data.product_id) {
                    product.likes_count += 1;
                    product.liked_by_user = true;
                    const message = `あなたの${product.productname}が${this.authName}さんにいいねされました。`;
                    const id = product.user.id;
                    this.inputNotification(message, id); //mixin[notification]参照
                    if (
                        product.likes_count % 10 == 0 &&
                        product.likes_count >= 10
                    ) {
                        const message = `あなたの${product.productname}が${product.likes_count}回いいねされました。`;
                        const id = product.user.id;
                        this.inputNotification(message, id); //mixin[notification]参照
                    }
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

            this.productList = this.productList.map(product => {
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
.Home {
    margin: 0 auto;
    margin-top: 0;
    display: flex;
    width: 100%;
    align-items: center;
    flex-flow: column;
}
.productsList {
    margin-top: 30px;
    display: flex;
    flex-flow: row wrap;
    align-content: flex-start;
}
</style>
