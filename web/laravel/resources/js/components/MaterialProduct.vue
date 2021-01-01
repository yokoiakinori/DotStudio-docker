<template>
	<div :style="productstyle">
		<div class="card">
			<transition>
				<div v-show="selectCheck" class="select"></div>
			</transition>
			<Picture
				:colors="colors"
				:product="product"
				:mountedStatus="mountedStatus"
				:alldot="alldot"
			></Picture>
		</div>
	</div>
</template>

<script>
import Picture from '../components/Products/Pictue.vue';
export default {
	components: {
		Picture,
	},
	props: {
		product: {},
		productstyle: {
			width: 0,
			height: 0,
		},
		selectCheck: Boolean,
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
	},
	created() {
		this.$nextTick(function() {
			this.mountedStatus = true;
		});
	},
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
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
		border: solid 1px rgba($maincolor, 0.5);
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
.select {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	border-radius: 15px;
	background-color: rgba($maincolor, 0.2);
}
.v-enter-active,
.v-leave-active {
	transition: opacity 0.3s;
}
.v-enter, .v-leave-to /* .fade-leave-active below version 2.1.8 */ {
	opacity: 0;
}
</style>
