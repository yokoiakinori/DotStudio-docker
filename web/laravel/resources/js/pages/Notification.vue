<template>
	<div>
		<h2 class="pageTitle">通知一覧</h2>
		<ul class="notificationList">
			<li v-for="(notification, index) in notifications" :key="notification.id">
				<p>{{ notification.message }}</p>
				<p class="created_at">{{ notification.created_at }}</p>
				<button class="formButton" @click="deleteNotification(index)">
					確認済
					<i class="fas fa-check"></i>
				</button>
			</li>
		</ul>
	</div>
</template>

<script>
import Axios from 'axios';
export default {
	data() {
		return {
			notifications: [],
		};
	},
	watch: {
		$route: {
			async handler() {
				this.$store.commit('randing/loadingSwitch', true);
				await this.showNotifications();
				this.$store.commit('randing/loadingSwitch', false);
			},
			immediate: true,
		},
	},
	methods: {
		async showNotifications() {
			const response = await axios.get('/api/notification');
			this.notifications = response.data;
		},
		async deleteNotification(index) {
			const id = this.notifications[index].id;
			const response = await axios.delete(`/api/notification/${id}`);
			this.notifications.splice(index, 1);
		},
	},
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.notificationList {
	width: 600px;
	margin: 0 auto;
	li {
		height: 50px;
		padding: 15px 5px;
		border-top: solid 1px $maincolor;
		display: flex;
		flex-flow: column wrap;
		justify-content: center;
		p {
			width: 490px;
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
.created_at {
	font-size: 14px;
	color: rgba($maincolor, 0.5);
}
</style>
