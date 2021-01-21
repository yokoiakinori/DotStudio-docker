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
            <div class="evaluations">
                <ThumbnailImage :user="product.user" class="thumbnail" />
                <p>
                    <i class="fas fa-heart heartIcon"></i
                    >{{ product.likes_count }}
                </p>
                <p><i class="fas fa-eye"></i>{{ product.countview }}</p>
            </div>
        </div>
        <div class="comments">
            <h2>Comments</h2>
            <ul v-if="product.comments.length > 0">
                <CommentListItem
                    v-for="comment in product.comments"
                    :key="comment.id"
                    :comment="comment"
                />
            </ul>
            <p v-else>まだコメントはありません。</p>
            <form
                v-if="isLogin"
                @submit.prevent="addComment"
                class="commentForm"
            >
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
import ThumbnailImage from "../components/ThumbnailImage.vue";
import CommentListItem from "../components/CommentListItem.vue";
import notification from "../mixin/notification";
export default {
    mixins: [notification],
    components: {
        Product,
        ProductTag,
        ThumbnailImage,
        CommentListItem
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
        },
        authName() {
            return this.$store.getters["auth/username"];
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
            const message = `${this.authName}さんがコメントしました。「${this.commentContent}」`;
            const id = this.product.user.id;
            this.inputNotification(message, id); //mixin[notification]参照
            this.commentContent = "";
            this.commentErrors = null;

            if (response.status !== CREATED) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }

            this.product.comments = [response.data, ...this.product.comments];
        },
        onMaterialClick() {
            if (!this.$store.getters["auth/check"]) {
                alert("スタンプ機能を使うにはログインしてください。");
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
            const message = `${this.authName}さんがあなたの${this.product.productname}をスタンプとしてダウンロードしました。`;
            const id = this.product.user.id;
            this.inputNotification(message, id); //mixin[notification]参照
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
.thumbnail {
    width: 45px;
    height: auto;
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
    width: 450px;
}
.commentForm {
    background-color: white;
    display: flex;
    flex-flow: column;
    align-items: center;
    padding: 30px;
    border-radius: 10px;
    width: 100%;
    textarea {
        padding: 10px;
        width: 100%;
        height: 85px;
        border: solid 1.5px rgba($color: $maincolor, $alpha: 0.6);
        border-radius: 5px;
        margin-bottom: 20px;
    }
}
.evaluations {
    display: flex;
    align-items: center;
    a {
        margin-right: 15px;
    }
    i {
        margin-right: 5px;
    }
    p {
        font-size: 16px;
        margin-right: 30px;
    }
}
</style>
