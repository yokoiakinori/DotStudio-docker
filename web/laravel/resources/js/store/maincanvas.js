import Axios from "axios";
const state = {
    allDotVolume: 900,
    currentMaterial: {},
    currentProduct: 0,
    paletColors: [
        "white",
        "black",
        "rgb(40%, 40%, 40%)",
        "rgb(70%, 70%, 70%)",
        "#f45757",
        "#27c227",
        "#4587d4",
        "#f3d54f",
        "#f488e1",
        "#57dcd5",
        "#62c3ae",
        "#e78f34",
        "#a03ee4"
    ],
    drawingColor: 1,
    drawingTool: "brush",
    lineDotVolume: 30,
    saveMaterial: [],
    saveStatus: false,
    usedMaterial: String
};

const getters = {};

const mutations = {
    drawingColor(state, color) {
        state.drawingColor = color;
    },
    drawingTool(state, tool) {
        state.drawingTool = tool;
    },
    resetDrawing(state) {
        state.drawingColor = 1;
        state.drawingTool = "brush";
    },
    productSave(state) {
        state.saveStatus = !state.saveStatus;
    },
    resetProduct(state) {
        state.currentProduct = 0;
    },
    setCurrentMaterial(state, current) {
        state.currentMaterial = current;
    },
    setCurrentProduct(state, current) {
        state.allDotVolume = current.alldot;
        state.currentProduct = current.id;
        state.lineDotVolume = current.linedot;
        state.usedMaterial = current.usedmaterial;
    },
    setSaveMaterial(state, current) {
        state.saveMaterial.push(current);
    }
};

const actions = {};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
