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
import { mapState } from "vuex";
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
            products: [],
            currentPage: 0,
            lastPage: 0,
            routerPath: "/search"
        };
    },
    computed: mapState({
        searchKeyword: state => state.search.keyword
    }),
    watch: {
        $route: {
            async handler() {
                if (this.searchKeyword == null) {
                    this.$router.push("/");
                }
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
                `/api/search/?page=${this.page}&keyword=${this.searchKeyword}`
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

<style></style>
