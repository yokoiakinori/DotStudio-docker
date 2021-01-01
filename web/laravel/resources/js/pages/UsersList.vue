<template>
	<div>
		<h2 class="pageTitle">ユーザー一覧</h2>
		<ul>
			<UserListItem
				v-for="(user, index) in users"
				:key="user.id"
				:index="index"
				:user="user"
			></UserListItem>
		</ul>
		<Pagination :current-page="currentPage" :last-page="lastPage" routerPath="/userslist" />
	</div>
</template>

<script>
import Pagination from '../components/Pagination.vue';
import UserListItem from '../components/UserListItem.vue';
import Axios from 'axios';
import { OK } from '../util';
export default {
	components: {
		Pagination,
		UserListItem,
	},
	props: {
		page: {
			type: Number,
			required: false,
			default: 1,
		},
	},
	data() {
		return {
			users: [],
			currentPage: 0,
			lastPage: 0,
			maxwidth: 400,
		};
	},
	methods: {
		async showUsers() {
			const response = await axios.get(`/api/users/list/?page=${this.page}`);
			if (response.status !== OK) {
				this.$store.commit('error/setCode', response.status);
				return false;
			}
			this.users = response.data.data;
			this.currentPage = response.data.current_page;
			this.lastPage = response.data.last_page;
		},
	},
	watch: {
		$route: {
			async handler() {
				this.$store.commit('randing/loadingSwitch', true);
				await this.showUsers();
				this.$store.commit('randing/loadingSwitch', false);
			},
			immediate: true,
		},
	},
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
ul {
	margin: 0;
	padding: 40px 0;
	display: flex;
	flex-flow: column;
	align-items: center;
}
</style>
