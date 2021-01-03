<template>
    <ul>
        <TodisplayDot
            v-for="dot in alldot"
            :key="dot"
            :color="Number(colors[dot - 1])"
            :lineDotVolume="product.linedot"
            :mountedStatus="mountedStatus"
        ></TodisplayDot>
    </ul>
</template>

<script>
import TodisplayDot from "./TodisplayDot.vue";
export default {
    components: {
        TodisplayDot
    },
    props: {
        alldot: Number,
        colors: {},
        mountedStatus: Boolean,
        product: {}
    }
};
</script>

<style></style>
