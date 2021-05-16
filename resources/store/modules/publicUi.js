const state = {
    gdpr: false,
}

const getters = {

}

const actions = {

}

const mutations = {
    gdprAccepted(state) {
        state.gdpr = true;
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
