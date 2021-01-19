<template>
    <img :src="user.userthumbnail.url" :alt="`${user.name}のサムネイル`" />
</template>

<script>
export default {
    props: {
        user: {}
    }
};
</script>

<style></style>
