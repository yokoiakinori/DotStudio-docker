<template>
    <div>
        <ThumbnailImage :user="comment.user" class="thumbnail" />
        <div class="content">
            <p class="name">{{ comment.user.name }}</p>
            <p>{{ comment.content }}</p>
        </div>
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
    height: 70px;
    margin-right: 20px;
}
.content {
    display: flex;
    flex-flow: column;
}
.name {
    font-size: 13px;
}
</style>
