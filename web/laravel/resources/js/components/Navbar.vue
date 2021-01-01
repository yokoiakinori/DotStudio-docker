<template>
    <nav class="navbar navbar__shadow">
        <RouterLink class="navbar__brand" to="/">
            Dotstudio
        </RouterLink>
        <div class="navbar__menu">
            <div class="navbar__item">
                <RouterLink :to="{ name: 'rank-users', query: { page: 1 } }">
                    <i class="fas fa-crown"></i>
                    ランキング
                </RouterLink>
            </div>
            <div v-if="isLogin" class="navbar__item">
                <RouterLink class="button" to="/drawing?page=1">
                    <i class="fas fa-paint-brush"></i>
                    投稿する
                </RouterLink>
            </div>
            <div v-if="isLogin" class="navbar__item">
                <RouterLink class="circlebutton" to="/notification">
                    <i class="fas fa-bell"></i>
                </RouterLink>
            </div>
            <RouterLink
                v-if="isLogin"
                :to="`/users/${id}`"
                class="thumbnail__link"
            >
                <img
                    :src="thumbnail"
                    :alt="`${name}のサムネイル`"
                    class="thumbnail"
                />
            </RouterLink>
            <div v-else class="navbar__item">
                <RouterLink class="button--link" to="/login">
                    Login / Register
                </RouterLink>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        thumbnail() {
            return this.$store.getters["auth/thumbnail"];
        },
        id() {
            return this.$store.getters["auth/userid"];
        },
        name() {
            return this.$store.getters["auth/username"];
        }
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
.circlebutton {
    transition-duration: 0.3s;
    i {
        font-size: 20px;
        vertical-align: center;
        color: rgba($color: $maincolor, $alpha: 0.7);
    }
    &:hover {
        i {
            color: rgba($maincolor, $alpha: 1);
        }
    }
}
.thumbnail__link {
    width: 30px;
    height: 30px;
}
</style>
