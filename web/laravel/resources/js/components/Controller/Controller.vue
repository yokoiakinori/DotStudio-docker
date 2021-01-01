<template>
    <div id="controller">
        <UserProduction />
        <ColorPalet></ColorPalet>
        <DrawTool></DrawTool>
    </div>
</template>

<script>
import ColorPalet from "./Colorpalet/ColorPalet.vue";
import UserProduction from "../ProductionsList/UserProductions.vue";
import DrawTool from "./DrawTool/DrawTool.vue";

export default {
    components: {
        ColorPalet,
        UserProduction,
        DrawTool
    }
};
</script>

<style lang="scss" scoped>
#controller {
    width: 30%;
    height: 60vw;
    max-height: 660px;
    margin-right: 5%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}
</style>
