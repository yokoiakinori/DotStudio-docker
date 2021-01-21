<template>
    <router-link :to="`/users/${user.id}`" class="thumbnaillink">
        <img
            :src="user.userthumbnail.url"
            :alt="`${user.name}のサムネイル`"
            class="thumbnail"
        />
    </router-link>
</template>

<script>
export default {
    props: {
        user: {}
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
</style>
