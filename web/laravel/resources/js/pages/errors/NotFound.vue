<template>
    <div>
        <p>お探しのページは見つかりませんでした。</p>
        <router-link to="/" exact>トップページへ戻る</router-link>
    </div>
</template>

<script>
export default {
    watch: {
        $route: {
            async handler() {
                this.$store.commit("randing/loadingSwitch", true);
                this.$store.commit("randing/loadingSwitch", false);
            },
            immediate: true
        }
    }
};
</script>

<style lang="scss" scoped>
@import "../../../sass/common.scss";
div {
    height: 100px;
}
p {
    text-align: center;
    padding-top: 30px;
    margin: 0;
    margin-bottom: 15px;
}
a {
    opacity: 0.7;
    text-align: center;
    display: inline-block;
    width: 100%;
    transition-duration: 0.3s;
    &:hover {
        opacity: 0.5;
        transition-duration: 0.3s;
    }
}
</style>
