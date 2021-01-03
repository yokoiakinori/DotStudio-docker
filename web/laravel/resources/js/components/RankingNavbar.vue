<template>
    <nav class="navbar navbar__left">
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
                { string: "作品", path: "rank-product" }
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
</style>
