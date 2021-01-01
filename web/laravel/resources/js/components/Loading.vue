<template>
	<div class="fullview">
		<div class="loading-spacer"></div>
		<vue-loading type="spin" color="rgba(40,45,111,.5)" :size="{ width: '80px', height: '80px' }">
		</vue-loading>
	</div>
</template>

<script>
import { VueLoading } from 'vue-loading-template';

export default {
	name: 'loading',
	components: {
		VueLoading,
	},
};
</script>

<style lang="scss" scoped>
.fullview {
	height: 300px;
}
.loading-spacer {
	height: 80px;
}
</style>
