const state = {
    gdpr: false,
    pwa: false,
    pwa_support: false,
}

const getters = {

}

const actions = {

}

const mutations = {
    gdprAccepted(state) {
        state.gdpr = true;
    },
    pwaInstalled(state) {
        state.pwa = true;
    },
    pwaNotInstalled(state) {
        state.pwa = false;
    },
    pwaSupported(state) {
        state.pwa_support = true;
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
