<template>
    <div>
        <ThumbnailImage :user="comment.user" class="thumbnail" />
        <p>{{ comment.content }}</p>
        <p>{{ comment.user.name }}</p>
    </div>
</template>

<script>
import ThumbnailImage from "../components/ThumbnailImage.vue";
export default {
    components: {
        ThumbnailImage
    },
    props: {
        comment: {}
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
div {
    display: flex;
}
.thumbnail {
    width: 70px;
    height: auto;
}
</style>
