<template>
    <li :style="dotStyle" @click="draw" @mousemove="dragDraw"></li>
</template>

<script>
import { mapState } from "vuex";
export default {
    props: {
        inputColor: Number,
        dotId: Number,
        drawingJudgement: Boolean,
        lineDotVolume: Number
    },
    data() {
        return {
            dotStyle: {
                backgroundColor: "white",
                width: 0,
                height: 0
            },
            nowColor: 0
        };
    },
    methods: {
        dragDraw() {
            if (this.drawingJudgement) {
                this.draw();
            }
        },
        draw() {
            if (
                ["brush", "line", "square", "squareline"].includes(
                    this.drawingTool
                )
            ) {
                this.nowColor = this.drawingColor;
            } else if (this.drawingTool == "eraser") {
                this.nowColor = 0;
            }
        }
    },
    watch: {
        inputColor(val) {
            this.nowColor = val;
        },
        nowColor(val) {
            this.dotStyle.backgroundColor = this.colorPalet[val];
        },
        lineDotVolume(val) {
            this.dotStyle.width = 100 / val + "%";
            this.dotStyle.height = 100 / val + "%";
        },
        saveStatus() {
            this.$emit("saveProduct", {
                id: this.dotId,
                color: this.nowColor
            });
        }
    },
    computed: mapState({
        drawingColor: state => state.maincanvas.drawingColor,
        drawingTool: state => state.maincanvas.drawingTool,
        saveStatus: state => state.maincanvas.saveStatus,
        colorPalet: state => state.maincanvas.paletColors
    })
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
