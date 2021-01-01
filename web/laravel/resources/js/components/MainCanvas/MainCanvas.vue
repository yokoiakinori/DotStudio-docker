<template>
    <div id="MainCanvas">
        <ul>
            <Dot
                v-for="item in allCanvasDot"
                :key="item"
                :dotid="item"
                :drawingJudgement="drawingJudgement"
                :lineDotVolume="lineDotVolume"
                :color="color[item - 1]"
                @mousedown.native="dragStart(item)"
                @mouseup.native="dragEnd"
                @saveProduct="saveProduct"
            ></Dot>
        </ul>
    </div>
</template>

<script>
import Dot from "./Dot.vue";
import { mapState } from "vuex";
export default {
    components: {
        Dot
    },
    data() {
        return {
            allCanvasDot: 0,
            color: [],
            dots: [],
            drawingJudgement: false,
            fillColor: null,
            firstClick: null,
            lineDotVolume: 0,
            materialColor: null,
            secondClick: null
        };
    },
    computed: mapState({
        currentMaterial: state => state.maincanvas.currentMaterial,
        currentProduct: state => state.maincanvas.currentProduct,
        drawingColor: state => state.maincanvas.drawingColor,
        drawingTool: state => state.maincanvas.drawingTool,
        saveStatus: state => state.maincanvas.saveStatus,
        usedMaterial: state => state.maincanvas.saveMaterial
    }),
    watch: {
        async currentProduct(val) {
            //メニューからプロダクトが選択された時実行
            await this.beforeCurrentReset();
            this.allCanvasDot = this.$store.state.maincanvas.allDotVolume;
            this.$nextTick(function() {
                this.lineDotVolume = this.$store.state.maincanvas.lineDotVolume;
            });
            await this.deploymentDot(val);
        },
        currentMaterial() {
            this.setMaterialColor();
        },
        drawingTool(val) {
            if (val == "reset") {
                this.drawReset();
            }
        },
        fillColor() {
            this.fillDraw();
        },
        saveStatus() {
            this.$nextTick(function() {
                this.saveConnect();
            });
        },
        secondClick() {
            if (this.drawingTool == "line") {
                this.lineDraw();
            } else if (this.drawingTool == "square") {
                this.squareDraw();
            } else if (this.drawingTool == "squareline") {
                this.squarelineDraw();
            }
        }
    },
    methods: {
        async beforeCurrentReset() {
            this.color.length = 0;
            for (let i = 1; i <= this.allCanvasDot; i++) {
                this.color.push("white");
            }
            this.$nextTick(function() {
                this.color.length = 0;
            });
            await this.sleep();
        },
        async deploymentDot(val) {
            const response = await axios.post("/api/products/current", {
                id: val
            });
            for (let i = 1; i <= this.allCanvasDot; i++) {
                const currentcolor = response.data[i - 1];
                this.color.push(currentcolor);
            }
        },
        dragStart(val) {
            this.drawingJudgement = true;
            if (
                ["line", "square", "squareline"].includes(this.drawingTool) &&
                this.firstClick == null
            ) {
                this.firstClick = val;
            } else if (
                ["line", "square", "squareline"].includes(this.drawingTool) &&
                this.secondClick == null
            ) {
                this.secondClick = val; //check [watch:secondClick()]
            } else if (this.drawingTool == "fill") {
                this.fillColor = this.color[val - 1]; //check [watch:fillColor()]
            } else if (this.drawingTool == "stamp") {
                this.drawStamp(val);
            }
        },
        dragEnd() {
            this.drawingJudgement = false;
        },
        async saveConnect() {
            const response = await axios.post("/api/products/save", {
                currentProduct: this.currentProduct,
                dots: this.dots,
                usedMaterial: this.usedMaterial
            });
            this.dots.length = 0;
        },
        saveProduct(data) {
            //このdataは子コンポーネントDotから送られてくる
            this.dots.push(data);
        },
        sleep() {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, 250);
            });
        },

        //ツール系メソッド-----↓↓↓
        drawReset() {
            const answer = confirm("初期化してもよろしいですか？");
            if (answer) {
                this.color.length = 0;
                for (let i = 1; i <= this.allCanvasDot; i++) {
                    this.color.push("white");
                    this.$nextTick(function() {
                        this.color.length = 0;
                    });
                }
            }
        },
        drawStamp(start) {
            if (this.currentMaterial.alldot > this.allCanvasDot) {
                alert("スタンプが描画領域よりも大きいです！");
            } else if (
                start % this.lineDotVolume >
                    this.lineDotVolume - this.currentMaterial.linedot + 1 ||
                start / this.lineDotVolume >
                    this.lineDotVolume - this.currentMaterial.linedot + 1
            ) {
                alert("この位置では描画領域よりはみ出してしまいます！");
            } else {
                const lineEnd = start + this.currentMaterial.linedot;
                for (let i = 0; i < this.currentMaterial.linedot; i++) {
                    for (let j = start; j <= lineEnd; j++) {
                        this.color[
                            j + i * this.lineDotVolume - 1
                        ] = this.materialColor[
                            j - start + i * this.currentMaterial.linedot
                        ];
                    }
                }
                if (!this.usedMaterial.includes(this.currentMaterial.id)) {
                    this.$store.commit(
                        "maincanvas/setSaveMaterial",
                        this.currentMaterial.id
                    );
                }
            }
        },
        fillDraw() {
            let checkInvalid = true;
            for (let i = 0; i <= this.lineDotVolume; i++) {
                for (let j = 1; j <= this.lineDotVolume; j++) {
                    if (
                        this.color[j + i * this.lineDotVolume - 1] ==
                        this.fillColor
                    ) {
                        this.color[
                            j + i * this.lineDotVolume - 1
                        ] = this.drawingColor;
                    }
                }
            }
            this.fillColor = null;
        },
        lineDraw() {
            const startNumber = Math.min(this.firstClick, this.secondClick);
            const lastNumber = Math.max(this.firstClick, this.secondClick);
            const differenceNumber = lastNumber - startNumber;
            //横
            if (differenceNumber < this.lineDotVolume) {
                for (let i = startNumber; i <= lastNumber; i++) {
                    this.color[i - 1] = this.drawingColor;
                }
            }
            //縦
            else if (differenceNumber % this.lineDotVolume == 0) {
                const count = differenceNumber / this.lineDotVolume;
                for (let i = 1; i <= count; i++) {
                    this.color[
                        startNumber + i * this.lineDotVolume - 1
                    ] = this.drawingColor;
                }
            }
            //斜め（45度）
            else if (differenceNumber % (this.lineDotVolume + 1) == 0) {
                const count = differenceNumber / this.lineDotVolume;
                for (let i = 1; i <= count; i++) {
                    this.color[
                        startNumber + i * (this.lineDotVolume + 1) - 1
                    ] = this.drawingColor;
                }
            }
            this.firstClick = null;
            this.secondClick = null;
        },
        setMaterialColor() {
            const colors = this.currentMaterial.colors.split("_");
            colors.pop();
            this.materialColor = colors;
        },
        squareDraw() {
            const startNumber = Math.min(this.firstClick, this.secondClick); //変数定義が重複しているがよりよい書き方がわからないため放置
            const lastNumber = Math.max(this.firstClick, this.secondClick);
            const differenceNumber = lastNumber - startNumber;
            const count = differenceNumber / this.lineDotVolume;
            const rowEndNumber =
                startNumber + (differenceNumber % this.lineDotVolume);
            for (let i = 0; i <= count; i++) {
                for (let j = startNumber; j <= rowEndNumber; j++) {
                    this.color[
                        j + i * this.lineDotVolume - 1
                    ] = this.drawingColor;
                }
            }
            this.firstClick = null;
            this.secondClick = null;
        },
        squarelineDraw() {
            const startNumber = Math.min(this.firstClick, this.secondClick); //変数定義が重複しているがよりよい書き方がわからないため放置
            const lastNumber = Math.max(this.firstClick, this.secondClick);
            const differenceNumber = lastNumber - startNumber;
            const count = differenceNumber / this.lineDotVolume;
            const rowEndNumber =
                startNumber + (differenceNumber % this.lineDotVolume);
            const columnEndNumber =
                lastNumber - (differenceNumber % this.lineDotVolume);
            for (let i = startNumber; i <= rowEndNumber; i++) {
                this.color[i - 1] = this.drawingColor;
            }
            for (let i = 1; i <= count; i++) {
                this.color[
                    startNumber + i * this.lineDotVolume - 1
                ] = this.drawingColor;
            }
            for (let i = columnEndNumber; i <= lastNumber; i++) {
                this.color[i - 1] = this.drawingColor;
            }
            for (let i = 1; i <= count; i++) {
                this.color[
                    rowEndNumber + i * this.lineDotVolume - 1
                ] = this.drawingColor;
            }
            this.firstClick = null;
            this.secondClick = null;
        }
        //ツール系メソッド-----↑↑↑
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import "../../../sass/common.scss";
#MainCanvas {
    width: 60%;
    height: 60vw;
    max-height: 660px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: white;
    box-shadow: 2px 2px 3px rgba($maincolor, 0.15);
    ul {
        width: 90%;
        height: 90%;
        padding: 0;
        display: flex;
        list-style-type: none;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
        margin: 0;
    }
}
</style>
