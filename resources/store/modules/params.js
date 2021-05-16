const state = {
    userParams: {
        gender: '',
        age: '',
        weight: '',
        heightCM: '',
        current_weight: '',
        maintain_weight: ''
    },
    initialGoal: {
        calories: '',
        goal: 0,
        selectedGoal: ''
    }
}

const getters = {
    getUserParams: (state) => {
        return state.userParams;
    },
    getInitialGoal: (state) => {
        return state.initialGoal;
    }
}

const mutations = {
    setUserParam: (state, param) => {
        state.userParams[param.key] = param.value;
    },
    setGoal: (state, goal) => {
        state.initialGoal = { ...goal };
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations
}
