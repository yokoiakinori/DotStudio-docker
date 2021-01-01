import Axios from 'axios';
const state = {
	allDotVolume: 900,
	currentMaterial: {},
	currentProduct: 0,
	drawingColor: 'black',
	drawingTool: 'brush',
	lineDotVolume: 30,
	saveMaterial: [],
	saveStatus: false,
	usedMaterial: String,
};

const getters = {};

const mutations = {
	drawingColor(state, color) {
		state.drawingColor = color;
	},
	drawingTool(state, tool) {
		state.drawingTool = tool;
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
	},
};

const actions = {};

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions,
};
