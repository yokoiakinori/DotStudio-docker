<template>
    <nav class="navbar">
        <div v-for="nav in navigations" :key="nav.string" class="navbar__item">
            <RouterLink :to="{ name: nav.path, query: { page: 1 } }">
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
</style>
