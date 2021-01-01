<template>
	<div :style="productstyle">
		<div class="card" @mouseleave="currentToggle" @mouseenter="currentToggle">
			<transition>
				<RouterLink :to="`/products/${product.id}`" v-show="current">
					<button
						:class="{ product_action_liked: product.liked_by_user }"
						@click.prevent="like"
						v-if="appearLike"
					>
						<i class="fas fa-heart heartIcon"></i>
						{{ product.likes_count }}
					</button>
				</RouterLink>
			</transition>
			<Picture :colors="colors" :product="product" :mountedStatus="mountedStatus" :alldot="alldot">
			</Picture>
		</div>
	</div>
</template>

<script>
import Picture from './Pictue.vue';
export default {
	components: {
		Picture,
	},
	props: {
		appearLike: false,
		product: {},
		productstyle: {
			width: 0,
			height: 0,
		},
	},
	data() {
		return {
			mountedStatus: false,
			current: false,
		};
	},
	computed: {
		colors() {
			return this.product.colors.split('_');
		},
		alldot() {
			return Number(this.product.alldot);
		},
	},
	methods: {
		currentToggle() {
			this.current = !this.current;
		},
		like() {
			this.$emit('like', {
				id: this.product.id,
				liked: this.product.liked_by_user,
			});
		},
	},
	created() {
		this.$nextTick(function() {
			this.mountedStatus = true;
		});
	},
};
</script>

<style lang="scss" scoped>
@import '../../../sass/common.scss';
div {
	display: flex;
	justify-content: center;
	align-items: center;
	.card {
		background-color: white;
		padding: 0;
		margin: 0;
		width: 90%;
		height: 90%;
		border-radius: 15px;
		position: relative;
		justify-content: center;
		align-items: center;
		box-shadow: 2px 2px 3px rgba($maincolor, 0.15);
		a {
			position: absolute;
			border-radius: 15px;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 10;
			background-color: rgba($maincolor, 0.2);
			display: flex;
			flex-direction: column;
			justify-content: flex-start;
			align-items: flex-end;
			button {
				transition-duration: 0.3s;
				margin: 18px;
				z-index: 20;
				width: 60px;
				height: 35px;
				border-radius: 5px;
				background-color: white;
			}
		}
		ul {
			padding: 0;
			margin: 0;
			width: 85%;
			height: 85%;
			display: flex;
			list-style-type: none;
			flex-direction: row;
			flex-wrap: wrap;
		}
	}
}
.product_action_liked {
	background-color: $heartcolor !important;
	transition-duration: 0.3s;
	color: white;
	i {
		color: white;
	}
}
.v-enter-active,
.v-leave-active {
	transition: opacity 0.3s;
}
.v-enter, .v-leave-to /* .fade-leave-active below version 2.1.8 */ {
	opacity: 0;
}
</style>
