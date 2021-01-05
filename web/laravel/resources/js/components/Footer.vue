<template>
    <footer class="footer">
        <button v-if="isLogin" @click="logout">
            ログアウト
        </button>
        <RouterLink v-else to="/login">
            Login / Register
        </RouterLink>
    </footer>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
    computed: {
        ...mapState({
            apiStatus: state => state.auth.apiStatus
        }),
        ...mapGetters({
            isLogin: "auth/check"
        })
    },
    methods: {
        async logout() {
            await this.$store.dispatch("auth/logout");
            if (this.apiStatus) {
                this.$router.push("/login");
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
footer {
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    button {
        padding: 10px;
        background-color: rgba($color: $maincolor, $alpha: 0.15);
        border-radius: 5px;
    }
}
</style>
