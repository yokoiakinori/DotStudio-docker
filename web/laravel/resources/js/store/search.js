const state = {
    keyword: null,
    tag: null
};

const getters = {};

const mutations = {
    setKeyword(state, keyword) {
        state.keyword = keyword;
    },
    setTag(state, tag) {
        state.tag = tag;
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
