<template>
    <li :style="dotStyle"></li>
</template>

<script>
import { mapState } from "vuex";
export default {
    props: {
        lineDotVolume: Number,
        color: Number,
        mountedStatus: Boolean
    },
    data() {
        return {
            dotStyle: {
                backgroundColor: "white",
                width: 0,
                height: 0
            }
        };
    },
    watch: {
        mountedStatus() {
            this.dotStyle.width = 100 / this.lineDotVolume + "%";
            this.dotStyle.height = 100 / this.lineDotVolume + "%";
            this.dotStyle.backgroundColor = this.colorPalet[this.color];
        }
    },
    computed: mapState({
        colorPalet: state => state.maincanvas.paletColors
    })
};
</script>

<style lang="scss" scoped>
li {
    list-style: none;
}
</style>
