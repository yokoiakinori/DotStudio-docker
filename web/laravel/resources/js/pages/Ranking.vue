<template>
    <div>
        <RankingNavbar />
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

<style></style>
