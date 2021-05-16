const state = {
    messages: [],
}

const getters = {
    getMessages(state) {
        return state.messages;
    },
}

const mutations = {
    pushMessage(state, message) {
        state.messages.push(message)
    },
    flushMessages(state) {
        state.messages = [];
    },
    setMessages(state, messages) {
        state.messages = messages;
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations
}
