<template>
    <div v-if="product" class="productDetail">
        <Product :product="product" :productstyle="productStyle" />
        <div class="productInformation">
            <h2>{{ product.productname }}</h2>
            <ul>
                <li v-for="tag in product.producttags" :key="tag.id">
                    <RouterLink to="/tagsearch?page=1">
                        <ProductTag :message="tag.message"></ProductTag>
                    </RouterLink>
                </li>
            </ul>
            <button
                class="formButton"
                v-if="isLogin && myProduct"
                @click="onMaterialClick"
            >
                素材としてダウンロード
            </button>
            <ul>
                <li v-for="material in product.usedmaterial" :key="material.id">
                    {{ material.user.name }}さんの{{
                        material.productname
                    }}が使用されています。
                </li>
            </ul>
        </div>
        <div class="comments">
            <h2>Comments</h2>
            <ul v-if="product.comments.length > 0">
                <li v-for="comment in product.comments" :key="comment.content">
                    <p>{{ comment.content }}</p>
                    <p>{{ comment.user.name }}</p>
                </li>
            </ul>
            <p v-else>まだコメントはありません。</p>
            <form v-if="isLogin" @submit.prevent="addComment">
                <div v-if="commentErrors">
                    <ul v-if="commentErrors.content">
                        <li v-for="msg in commentErrors.content" :key="msg">
                            {{ msg }}
                        </li>
                    </ul>
                </div>
                <textarea v-model="commentContent"></textarea>
                <div>
                    <button type="submit" class="formButton">
                        コメントをつける
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Axios from "axios";
import { OK, CREATED, UNPROCESSABLE_ENTITY } from "../util";
import Product from "../components/Products/Product.vue";
import ProductTag from "../components/ProductTag.vue";
export default {
    components: {
        Product,
        ProductTag
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            product: null,
            productStyle: {
                width: "500px",
                height: "500px"
            },
            commentContent: "",
            commentErrors: null
        };
    },
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        myProduct() {
            return this.product.user.id != this.$store.getters["auth/userid"];
        }
    },
    watch: {
        $route: {
            async handler() {
                this.$store.commit("randing/loadingSwitch", true);
                await this.showProduct();
                this.$store.commit("randing/loadingSwitch", false);
            },
            immediate: true
        }
    },
    methods: {
        async showProduct() {
            const response = await axios.get(`/api/product/${this.id}`);
            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }
            this.product = response.data;
        },
        async addComment() {
            const response = await axios.post(
                `/api/products/${this.id}/comments`,
                {
                    content: this.commentContent
                }
            );

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.commentErrors = response.data.errors;
                return false;
            }
            this.commentContent = "";
            this.commentErrors = null;

            if (response.status !== CREATED) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }

            this.product.comments = [response.data, ...this.product.comments];
        },
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

            this.products = this.products.map(product => {
                if (product.id == response.data.product_id) {
                    product.likes_count += 1;
                    product.liked_by_user = true;
                    if (
                        product.likes_count % 10 == 0 &&
                        product.likes_count >= 10
                    ) {
                        this.likedNotification(
                            product.productname,
                            product.likes_count,
                            product.user.id
                        );
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

            this.products = this.products.map(product => {
                if (product.id == response.data.product_id) {
                    product.likes_count -= 1;
                    product.liked_by_user = false;
                }
                return product;
            });
        },
        async likedNotification(name, count, id) {
            this.notification.id = id;
            this.notification.message = `あなたの${name}が${count}回いいねされました。`;
            const responsse = await axios.post(
                "/api/notification",
                this.notification
            );
        },
        onMaterialClick() {
            if (!this.$store.getters["auth/check"]) {
                alert("いいね機能を使うにはログインしてください。");
                return false;
            }
            this.materialAdd();
        },
        async materialAdd() {
            const response = await axios.put(
                `/api/material/${this.product.id}`
            );

            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
button {
    height: 40px;
    padding: 0 40px;
}
.productDetail {
    display: flex;
    flex-flow: row wrap;
    width: 1000px;
    margin: 0 auto;
    padding-top: 15px;
}
.button--liked {
    background-color: hotpink;
}
.productInformation {
    width: 500px;
    height: 400px;
    margin-top: 15px;
    ul {
        display: flex;
        li {
            display: flex;
            align-items: center;
        }
    }
}
.comments {
    margin: 0 0 30px 30px;
}
</style>
