<template>
    <nav class="navbar navbar__shadow">
        <RouterLink class="navbar__brand" to="/">
            Dotstudio
        </RouterLink>
        <div class="navbar__menu">
            <form @submit.prevent="setKeyword" class="searchbox">
                <input
                    type="text"
                    placeholder="タグ、作品名"
                    v-model="searchText"
                />
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
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
    data() {
        return {
            searchText: null
        };
    },
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
    },
    methods: {
        setKeyword() {
            if (this.searchText != null) {
                this.$store.commit("search/setKeyword", this.searchText);
                this.$router.push("/search");
            }
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
.searchbox {
    display: flex;
    align-items: center;
    background-color: rgba($maincolor, 0.3);
    width: 200px;
    height: 27px;
    margin-right: 40px;
    border-radius: 30px;
    i {
        display: inline-block;
        text-align: center;
        vertical-align: bottom;
        line-height: 20px;
        font-size: 17px;
        height: 20px;
        width: 20px;
        transform: translateY(4%);
    }
    input {
        margin-left: 15px;
        width: 145px;
    }
}
.thumbnail__link {
    width: 30px;
    height: 30px;
}
</style>
