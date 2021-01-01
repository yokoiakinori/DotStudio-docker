import Axios from "axios";
import {
	OK,
	CREATED,
	UNPROCESSABLE_ENTITY
} from '../util';

const state = {
	user: null,
	apiStatus: null,
	loginErrorMessages: null,
	registerErrorMessages: null
};

const getters = {
	check: state => !!state.user,
	username: state => state.user ? state.user.name : '',
	thumbnail: state => state.user ? state.user.userthumbnail.url : '',
	userid: state => state.user ? state.user.id : '',
	followees: state => state.user ? state.user.followers : '',
};

const mutations = {
	setUser(state, user) {
		state.user = user;
	},
	setApiStatus(state, status) {
		state.apiStatus = status;
	},
	setLoginErrorMessages(state, messages) {
		state.loginErrorMessages = messages;
	},
	setRegisterErrorMessages(state, messages) {
		state.registerErrorMessages = messages;
	},
	updateUser(state, user) {
		state.user.name = user.name
	}
};

const actions = {
	async register(context, data) {
		context.commit('setApiStatus', null);
		const response1 = await axios.post('/api/register', data);
		const response2 = await axios.post('/api/thumbnail');
		const response = await axios.get('/api/user');
		const user = response.data || null;

		if (response1.status === CREATED) {
			context.commit('setApiStatus', true);
			context.commit('setUser', user);
			return false;
		}

		context.commit('setApiStatus', false);
		if (response1.status === UNPROCESSABLE_ENTITY) {
			context.commit('setRegisterErrorMessages', response1.data.errors);
		} else {
			context.commit('error/setCode', response1.status, {
				root: true
			});
		}
	},

	async login(context, data) {
		context.commit('setApiStatus', null);
		const login = await axios.post('/api/login', data);
		const response = await axios.get('/api/user');
		const user = response.data || null;

		if (response.status === OK) {
			context.commit('setApiStatus', true);
			context.commit('setUser', user);
			return false;
		}

		context.commit('setApiStatus', false);
		if (response.status === UNPROCESSABLE_ENTITY) {
			context.commit('setLoginErrorMessages', response.data.errors);
		} else {
			context.commit('error/setCode', response.status, {
				root: true
			});
		}
	},

	async logout(context) {
		context.commit('setApiStatus', null);
		const response = await axios.post('/api/logout');

		if (response.status === OK) {
			context.commit('setApiStatus', true);
			context.commit('setUser', null);
			return false;
		}

		context.commit('setApiStatus', false);
		context.commit('error/setCode', response.status, {
			root: true
		});
	},

	async currentUser(context) {
		context.commit('setApiStatus', null);
		const response = await axios.get('/api/user');
		const user = response.data || null;

		if (response.status === OK) {
			context.commit('setApiStatus', true);
			context.commit('setUser', user);
			return false;
		}

		context.commit('setApiStatus', false);
		context.commit('error/setCode', response.status, {
			root: true
		});
	},
};

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
};
