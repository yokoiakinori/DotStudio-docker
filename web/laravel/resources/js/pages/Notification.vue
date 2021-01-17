<template>
    <div>
        <h2 class="pageTitle">通知一覧</h2>
        <ul class="notification__List">
            <li
                v-for="notification in notifications"
                :key="notification.id"
                :class="{ notification__unchecked: !notification.checked }"
            >
                <div>
                    <p>{{ notification.message }}</p>
                    <p class="created_at">{{ notification.created_at }}</p>
                </div>
                <button
                    class="formButton"
                    @click="checkedNotification(notification.id)"
                    v-if="!notification.checked"
                >
                    確認
                    <i class="fas fa-check"></i>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
import Axios from "axios";
export default {
    data() {
        return {
            notifications: []
        };
    },
    watch: {
        $route: {
            async handler() {
                this.$store.commit("randing/loadingSwitch", true);
                await this.showNotifications();
                this.$store.commit("randing/loadingSwitch", false);
            },
            immediate: true
        }
    },
    methods: {
        async showNotifications() {
            const response = await axios.get("/api/notification");
            this.notifications = response.data;
        },
        async checkedNotification(index) {
            console.log(index);
            const response = await axios.put(`/api/notification/${index}`);
        }
    }
};
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
.notification {
    &__List {
        width: 800px;
        margin: 0 auto;
        display: flex;
        flex-flow: column;
        li {
            height: 80px;
            padding: 15px;
            border-top: solid 1px $maincolor;
            display: flex;
            justify-content: space-between;
            align-items: center;
            div {
                display: flex;
                flex-flow: column;
            }
            button {
                width: 100px;
                height: 40px;
                padding: 0;
                border-radius: 5px;
                border: solid 1px $maincolor;
            }
        }
    }
    &__unchecked {
        background-color: rgba($color: $subcolor, $alpha: 0.1);
    }
}
.created_at {
    font-size: 12px;
    color: rgba($maincolor, 0.5);
}
</style>
