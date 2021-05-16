const state = {
    content: []
}

const getters = {
    getContent(state) {
        return state.content
    }
}

const actions = {
    async fetchGetContent({ commit }) {
        try {
            let res = await this.$http.get(`/api/content`)

            this.setSiteContent(res.data.data)
        } catch (e) {
            console.log(e);
        }
    }
}

const mutations = {
    setContent(state, payload) {
        state.content = payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
