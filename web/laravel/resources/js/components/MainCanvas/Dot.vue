<template>
	<li :style="dotStyle" @click="draw" @mousemove="dragDraw"></li>
</template>

<script>
import { mapState } from 'vuex';
export default {
	props: {
		color: String,
		dotid: Number,
		drawingJudgement: Boolean,
		lineDotVolume: Number,
	},
	data() {
		return {
			dotStyle: {
				backgroundColor: 'white',
				width: 0,
				height: 0,
			},
		};
	},
	methods: {
		dragDraw() {
			if (this.drawingJudgement) {
				this.draw();
			}
		},
		draw() {
			if (['brush', 'line', 'square', 'squareline'].includes(this.drawingTool)) {
				this.dotStyle.backgroundColor = this.drawingColor;
			} else if (this.drawingTool == 'eraser') {
				this.dotStyle.backgroundColor = 'white';
			}
		},
	},
	watch: {
		color(val) {
			if (typeof val == 'string') {
				this.dotStyle.backgroundColor = val;
			}
		},
		lineDotVolume(val) {
			this.dotStyle.width = 100 / val + '%';
			this.dotStyle.height = 100 / val + '%';
		},
		saveStatus() {
			this.$emit('saveProduct', { id: this.dotid, color: this.dotStyle.backgroundColor });
		},
	},
	computed: mapState({
		drawingColor: state => state.maincanvas.drawingColor,
		drawingTool: state => state.maincanvas.drawingTool,
		saveStatus: state => state.maincanvas.saveStatus,
	}),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
li {
	transition-property: background-color;
	transition-duration: 0.3s;
	list-style: none;
}
</style>
