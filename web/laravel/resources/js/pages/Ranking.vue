<template>
    <div class="wrap">
        <RankingNavbar class="nav" />
        <RouterView />
    </div>
</template>

<script>
import RankingNavbar from "../components/RankingNavbar.vue";
export default {
    components: {
        RankingNavbar
    }
};
</script>

<style lang="scss" scoped>
.wrap {
    display: flex;
    flex-flow: column;
    align-items: center;
}
.nav {
    width: 800px;
}
</style>
