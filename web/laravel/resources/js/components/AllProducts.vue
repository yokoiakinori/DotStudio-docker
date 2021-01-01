<template>
	<div class="Home">
		<div class="productsList" :style="style">
			<Product
				v-for="product in products"
				:key="product.id"
				:product="product"
				:appearLike="true"
				@like="onLikeClick"
				:productstyle="productStyle"
			/>
		</div>
		<Pagination :current-page="currentPage" :last-page="lastPage" :routerPath="routerPath" />
	</div>
</template>

<script>
import Pagination from './Pagination.vue';
import Product from './Products/Product.vue';
import Axios from 'axios';
import { OK } from '../util';
export default {
	components: {
		Product,
		Pagination,
	},
	props: {
		page: {
			type: Number,
			required: false,
			default: 1,
		},
		routerPath: String,
		products: null,
		currentPage: 0,
		lastPage: 0,
	},
	data() {
		return {
			maxwidth: 900,
			style: {
				width: '900px',
				height: '1500px',
			},
			notification: {
				message: String,
				id: Number,
			},
		};
	},
	computed: {
		productStyle() {
			const product = `${this.maxwidth / 3}px`;
			return {
				width: product,
				height: product,
			};
		},
	},
	methods: {
		onLikeClick({ id, liked }) {
			if (!this.$store.getters['auth/check']) {
				alert('いいね機能を使うにはログインしてください。');
				return false;
			}

			if (liked) {
				this.unlike(id);
			} else {
				this.like(id);
			}
		},
		async like(id) {
			const response = await axios.put(`/api/products/${id}/like`);

			if (response.status !== OK) {
				this.$store.commit('error/setCode', response.status);
				return false;
			}

			this.products = this.products.map(product => {
				if (product.id == response.data.product_id) {
					product.likes_count += 1;
					product.liked_by_user = true;
					if (product.likes_count % 10 == 0 && product.likes_count >= 10) {
						this.likedNotification(product.productname, product.likes_count, product.user.id);
					}
				}
				return product;
			});
		},
		async unlike(id) {
			const response = await axios.delete(`/api/products/${id}/like`);

			if (response.status !== OK) {
				this.$store.commit('error/setCode', response.status);
				return false;
			}

			this.products = this.products.map(product => {
				if (product.id == response.data.product_id) {
					product.likes_count -= 1;
					product.liked_by_user = false;
				}
				return product;
			});
		},
		async likedNotification(name, count, id) {
			this.notification.id = id;
			this.notification.message = `あなたの${name}が${count}回いいねされました。`;
			const responsse = await axios.post('/api/notification', this.notification);
		},
	},
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.Home {
	margin: 0 auto;
	margin-top: 0;
	display: flex;
	width: 100%;
	align-items: center;
	flex-flow: column;
}
.productsList {
	margin-top: 30px;
	display: flex;
	flex-flow: row wrap;
	align-content: flex-start;
}
</style>
