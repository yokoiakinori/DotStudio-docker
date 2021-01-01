<template>
    <div>
        <transition>
            <modalWindow
                v-if="modalWindowCheck"
                @closeModal="modalToggle"
                @formEnter="createProduction"
            >
                <label for="productname">作品名</label>
                <input type="text" v-model="productname" />
                <label for="linedot"
                    >1列に配置するドット数（合計：{{ alldot }}ドット）</label
                >
                <input type="number" v-model="linedot" />
                <label for="tags">タグ（カテゴリー、イメージ）</label>
                <input type="text" v-model="producttagstring" />
                <ul class="tagList">
                    <li v-for="(productTag, index) in productTags" :key="index">
                        <ProductTag :message="productTag"></ProductTag>
                    </li>
                </ul>
            </modalWindow>
        </transition>
        <ul class="UserProductions" v-if="productListLength > 0">
            <li
                v-for="product in productionList"
                :key="product.uniquekey"
                @click="currentProduct = product.uniquekey"
                :class="{
                    'UserProductions--active':
                        currentProduct === product.uniquekey
                }"
                class="UserProductions__list UserProductions__item"
            >
                <p class="UserProductions__item__name">
                    {{ product.productname }}
                </p>
                <button
                    v-show="currentProduct === product.uniquekey"
                    class="save-button"
                    @click="productSave"
                >
                    保存
                </button>
                <i
                    class="fas fa-trash-alt"
                    v-show="currentProduct === product.uniquekey"
                    @click="productDelete"
                ></i>
            </li>
            <li
                id="createProduction"
                @click="modalToggle"
                v-if="this.currentPage == this.lastPage"
                class="UserProductions__list"
            >
                <span>＋</span>ここをクリックで新規作成
            </li>
            <li class="pagination UserProductions__list">
                <Pagination
                    :current-page="currentPage"
                    :last-page="lastPage"
                    routerPath="/drawing"
                />
            </li>
        </ul>
    </div>
</template>

<script>
import ModalWindow from "../ModalWindow.vue";
import Pagination from "../Pagination.vue";
import ProductTag from "../ProductTag.vue";
import Axios from "axios";
import { OK, CREATED } from "../../util";
export default {
    components: {
        ModalWindow,
        Pagination,
        ProductTag
    },
    data() {
        return {
            currentPage: 0,
            lastPage: 0,
            currentProduct: String,
            linedot: 0,
            modalWindowCheck: false,
            productionList: [],
            productname: "",
            productTags: [],
            producttagstring: ""
        };
    },
    computed: {
        alldot() {
            return this.linedot ** 2;
        },
        productListLength() {
            return this.productionList.length;
        }
    },
    watch: {
        $route: {
            async handler() {
                await this.reset();
                await this.showProductsList();
                this.$store.commit("randing/loadingSwitch", false);
                if (this.productListLength == 0) {
                    this.modalToggle();
                }
            },
            immediate: true
        },
        currentProduct(val) {
            const current = this.productionList.find(
                element => element.uniquekey == val
            );
            this.$store.commit("maincanvas/setCurrentProduct", {
                alldot: current.alldot,
                linedot: current.linedot,
                id: current.id,
                usedmaterial: current.usedmaterial
            });
        },
        producttagstring: function(val) {
            const productTags = val.split(/\s+/);
            this.productTags = productTags;
        }
    },
    methods: {
        async createProduction() {
            const today = new Date();
            const getUniqueKey = `${today.getFullYear()}${today.getMonth()}${today.getDate()}${today.getHours()}${today.getMinutes()}${today.getSeconds()}${Math.random()
                .toString(36)
                .slice(-8)}`;
            const createProduct = {
                productname: this.productname,
                alldot: Number(this.alldot),
                linedot: Number(this.linedot),
                tags: this.productTags,
                uniquekey: getUniqueKey
            };

            const response = await axios.post("/api/products", createProduct);

            if (response.status !== CREATED) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }

            this.modalToggle();
            this.showProductsList();
            const createdProduct = this.productionList.slice(-1)[0];
            this.currentProduct = createdProduct.uniquekey;

            const maxProductionList = 3;
            if (this.productionList.length < maxProductionList) {
                this.productionList.push(response.data);
            } else {
                this.$router.push(
                    `/drawing?page=${Number(this.$route.query.page) + 1}`
                );
            }
        },
        modalToggle() {
            this.reset();
            this.modalWindowCheck = !this.modalWindowCheck;
        },
        productSave() {
            this.$store.commit("maincanvas/productSave");
        },
        async productDelete() {
            const response = await axios.get(
                `/api/products/delete/${
                    this.productionList.find(
                        element => element.uniquekey == this.currentProduct
                    ).id
                }`
            );
            const productid = this.productionList.findIndex(
                element => element.uniquekey == this.currentProduct
            );

            const minProductionList = 1;
            if (this.productionList.length > minProductionList) {
                this.productionList.splice(productid, 1);
            } else {
                this.$router.push(
                    `/drawing?page=${Number(this.$route.query.page) - 1}`
                );
            }
        },
        async reset() {
            this.linedot = 0;
            this.productname = null;
            this.productTags.splice(0, this.productTags.length);
            this.producttagstring = "";
        },
        async showProductsList() {
            this.productionList.splice(0, this.productionList.length);
            const response = await axios.get(
                `/api/products?page=${this.$route.query.page}`
            );

            if (!response.data.data.length == 0) {
                for (let i = 0; i < response.data.data.length; i++) {
                    this.productionList.push(response.data.data[i]);
                }
                this.currentProduct = this.productionList[0].uniquekey;
                this.currentPage = response.data.current_page;
                this.lastPage = response.data.last_page;
            }
        }
    },
    beforeDestroy() {
        this.productionList.splice(0, this.productionList.length);
        this.$store.commit("maincanvas/resetProduct");
    }
};
</script>

<style lang="scss" scoped>
@import "../../../sass/common.scss";
div {
    width: 100%;
}
.UserProductions {
    width: 100%;
    height: 220px;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: start;
    flex-direction: column;
    align-items: center;
    &__list {
        font-size: 18px;
        width: 100%;
        height: 20%;
        list-style: none;
        display: flex;
        align-items: center;
        border-top: solid 1.2px $maincolor;
        transition-duration: 0.3s;

        span {
            margin: 0 20px;
        }
    }
    &__item {
        padding: 0 20px;
        justify-content: space-between;
        &__name {
            font-size: 17px;
            width: 65%;
        }
    }
    &--active {
        transition-duration: 0.3s;
        background-color: $maincolor;
        color: white;
        p {
            color: white;
        }
        button {
            color: white;
            width: 20%;
            border-right: solid 1px white;
        }
        i {
            color: white !important;
            margin-left: 10px;
        }
    }
}
#createProduction {
    color: rgba($maincolor, 0.6);
}
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s;
}
.v-enter,
.v-leave-to {
    opacity: 0;
}

label {
    width: 100%;
    font-size: 16px;
}
input {
    width: 100%;
    height: 45px;
    border-bottom: solid 1px $maincolor;
    margin-bottom: 30px;
}
.tagList {
    display: flex;
    height: 100px;
    padding: 0;
    flex-flow: row;
    justify-content: flex-start;
    align-items: flex-start;
    li {
        border: none;
        display: inline-block;
        margin-right: 20px;
    }
}
</style>
