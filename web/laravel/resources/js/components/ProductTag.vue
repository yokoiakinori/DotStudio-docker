<template>
    <div @click="tagset">
        <i class="fas fa-tag"></i>
        {{ message }}
    </div>
</template>

<script>
export default {
    props: {
        message: String
    },
    methods: {
        tagset() {
            this.$store.commit("search/setTag", this.message);
        }
    }
};
</script>

<style lang="scss" scoped>
i {
    margin-right: 7px;
}
</style>
