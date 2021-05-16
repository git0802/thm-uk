const state = {
    isLoading: false
}

const getters = {
    getIsLoading: (state) => {
        return state.isLoading;
    }
}

const actions = {
    loading({ commit }, value) {
        let preloader = document.getElementById('preloader');
        let body = document.body;
        commit('setIsLoading', value);
        if(value === true) {
            body.classList.add('overflow-hidden');
            preloader.style.display = 'block';
            preloader.classList.add("active-preloader");
        } else {
            body.classList.remove('overflow-hidden');
            preloader.style.display = 'none';
            preloader.classList.remove("active-preloader");
        }
    }
}

const mutations = {
    setIsLoading: (state, value) => {
        state.isLoading = value;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
