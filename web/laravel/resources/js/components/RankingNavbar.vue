<template>
    <nav class="navbar navbar__left">
        <h3><i class="fas fa-crown"></i>ランキング</h3>
        <div v-for="nav in navigations" :key="nav.string" class="navbar__item">
            <RouterLink :to="{ name: nav.path, query: { page: 1 } }" exact>
                {{ nav.string }}
            </RouterLink>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            navigations: [
                { string: "ユーザー", path: "rank-users" },
                { string: "作品（いいね数）", path: "rank-product-like" },
                { string: "作品（閲覧数）", path: "rank-product-watch" }
            ]
        };
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
.navbar__item {
    a {
        font-size: 17px;
        border-bottom: solid 2px $maincolor;
        padding: 5px;
    }
}
.router-link-active {
    font-weight: bold;
    opacity: 1 !important;
}
h3 {
    margin-right: 40px;
    i {
        margin-right: 5px;
    }
}
</style>
