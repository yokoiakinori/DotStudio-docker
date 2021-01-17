<template>
    <div>
        <div class="info">
            <i class="fas fa-tag"></i>
            <h3>
                <span>{{ searchTag }}</span> の検索結果
            </h3>
        </div>
        <AllProducts
            :products="products"
            :currentPage="currentPage"
            :lastPage="lastPage"
            :routerPath="routerPath"
        ></AllProducts>
    </div>
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
            routerPath: "/tagsearch"
        };
    },
    computed: mapState({
        searchTag: state => state.search.tag
    }),
    watch: {
        $route: {
            async handler() {
                if (this.searchTag == null) {
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
                `/api/tagsearch/?page=${this.page}&tag=${this.searchTag}`
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
.info {
    height: 80px;
    position: relative;
    display: flex;
    align-items: center;
    margin: 0 15px;
}
span {
    font-size: 25px;
}
i {
    font-size: 70px;
    color: rgba($color: $maincolor, $alpha: 0.2);
}
h3 {
    z-index: 2;
    position: absolute;
    left: 30px;
}
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
