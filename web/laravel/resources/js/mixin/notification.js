export default {
    data() {
        return {
            notification: {
                message: String,
                id: Number
            }
        };
    },
    methods: {
        async inputNotification(message, id) {
            this.notification.id = id;
            this.notification.message = message;
            const responsse = await axios.post(
                "/api/notification",
                this.notification
            );
        }
    }
};
