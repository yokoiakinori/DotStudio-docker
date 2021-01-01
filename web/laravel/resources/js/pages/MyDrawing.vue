<template>
    <div class="MyDrawing">
        <Controller></Controller>
        <MainCanvas></MainCanvas>
    </div>
</template>

<script>
import Controller from "../components/Controller/Controller.vue";
import MainCanvas from "../components/MainCanvas/MainCanvas.vue";

export default {
    components: {
        Controller,
        MainCanvas
    },
    props: { name: String },
    watch: {
        $route: {
            async handler() {
                this.$store.commit("randing/loadingSwitch", true);
            },
            immediate: true
        }
    }
};
</script>

<style lang="scss" scoped>
.MyDrawing {
    display: flex;
    justify-content: left;
    box-sizing: border-box;
    width: 100%;
    max-width: 1100px;
    padding: 30px 0 50px;
    margin: auto;
}
</style>
