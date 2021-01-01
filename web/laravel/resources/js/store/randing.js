const state = {
	loading: true
};

const getters = {};

const mutations = {
	loadingSwitch(state, status) {
		state.loading = status
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
