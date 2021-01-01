<template>
	<div>
		<ul>
			<li
				v-for="tool in tools"
				:key="tool.id"
				:class="{ active: currentToolId === tool.id }"
				@click="currentTool(tool.name, tool.id)"
			>
				<i :class="tool.class"></i>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	data() {
		return {
			currentToolId: 0,
			tools: [
				{ id: 0, class: 'fas fa-paint-brush brush', name: 'brush' },
				{ id: 1, class: 'fas fa-eraser', name: 'eraser' },
				{ id: 2, class: 'fas fa-fill', name: 'fill' },
				{ id: 3, class: 'fas fa-slash', name: 'line' },
				{ id: 4, class: 'fas fa-square', name: 'square' },
				{ id: 5, class: 'far fa-square', name: 'squareline' },
				{ id: 6, class: 'fas fa-stamp', name: 'stamp' },
				{ id: 7, class: 'fas fa-ban', name: 'reset' },
			],
		};
	},
	methods: {
		currentTool(toolname, id) {
			this.currentToolId = id;
			let drawingtool = toolname;
			this.$store.commit('maincanvas/drawingTool', drawingtool);
		},
	},
};
</script>

<style lang="scss" scoped>
@import '../../../../sass/common.scss';
div {
	width: 100%;
	ul {
		padding: 0;
		display: flex;
		flex-flow: row wrap;
		// justify-content: space-between;
		padding: 20px;
	}
}
i {
	color: rgba($maincolor, 0.3);
	font-size: 30px;
	margin: 10px;
}
.active {
	i {
		color: $maincolor;
	}
}
</style>
