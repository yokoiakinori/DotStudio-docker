<template>
    <AllProducts
        :products="products"
        :currentPage="currentPage"
        :lastPage="lastPage"
        :routerPath="routerPath"
    ></AllProducts>
</template>

<script>
import AllProducts from "../components/AllProducts.vue";
import Axios from "axios";
import { OK } from "../util";
export default {
    components: {
        AllProducts
    },
    props: {
        page: {
            type: Number,
            required: false,
            default: 1
        }
    },
    data() {
        return {
            currentPage: 0,
            lastPage: 0,
            products: [],
            routerPath: "/"
        };
    },
    watch: {
        $route: {
            async handler() {
                this.$store.commit("randing/loadingSwitch", true);
                await this.showProducts();
                this.$store.commit("randing/loadingSwitch", false);
            },
            immediate: true
        }
    },
    methods: {
        async showProducts() {
            const response = await axios.get(
                `/api/products/index/?page=${this.page}`
            );
            if (response.status !== OK) {
                this.$store.commit("error/setCode", response.status);
                return false;
            }
            this.products = response.data.data;
            this.currentPage = response.data.current_page;
            this.lastPage = response.data.last_page;
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
