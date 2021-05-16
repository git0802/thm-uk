const state = {
    is_mobile: false,
    right_drawer: true,
    right_drawer_desktop: null,
    left_drawer: true,
    test: 'not',
    overlay: false,
    points: {
        1337: {
            is_mobile: false,
            right_drawer: true,
            left_drawer: true,
            media: window.matchMedia("(min-width: 1201px)")
        },
        1200: {
            is_mobile: false,
            right_drawer: false,
            left_drawer: true,
            media: window.matchMedia("(min-width: 1021px) and (max-width: 1200px)")
        },
        1020: {
            is_mobile: true,
            right_drawer: false,
            left_drawer: false,
            media: window.matchMedia("(max-width: 1020px)")
        },
    },
    inspire: '',
    stats: null,
    isLoading: false
};

const getters = {

    getRightDrawer(state) {
        return state.right_drawer;
    },
    getLeftDrawer(state) {
        return state.left_drawer;
    },
    getMobile(state) {
        return state.is_mobile;
    },
    getOverlay(state) {
        return state.overlay;
    },
    getIsLoading(state) {
        return state.isLoading;
    },

};

const actions = {
    async fetchInspire({ commit }, http) {
        commit('setIsLoading', true);
        try {
            let res = await http.get(`/api/quote`)
            commit('setInspire', res.data.quote);
        } catch (e) {

        }
        commit('setIsLoading', false);

    },
    async fetchStats({ commit }, http) {
        commit('setIsLoading', true);

        try {
            let res = await http.get(`/api/stats/week`)
            commit('setStats', res.data.stats);
        } catch (e) {

        }
        commit('setIsLoading', false);

    }

};

const mutations = {
    setIsLoading(state, payload) {
        state.isLoading = payload
    },
    setRightDrawer(state, drawerState) {
        state.right_drawer = drawerState;
    },
    setLeftDrawer(state, drawerState) {
        state.left_drawer = drawerState;
    },
    setMobile(state, mobile) {
        state.is_mobile = mobile;
    },
    setOverlay(state, over) {
        state.overlay = over;
    },
    handleOverlay(state) {
        state.overlay = !state.overlay;
    },
    handleRightDrawer(state) {
        state.right_drawer = !state.right_drawer;
    },
    handleLeftDrawer(state) {
         state.left_drawer = !state.left_drawer;
    },
    handleResize(state) {
        Object.keys(state.points).forEach((key) => {
            if(state.points[key].media.matches) {
                state.left_drawer  = state.points[key].left_drawer;
                state.right_drawer = state.points[key].right_drawer;
                state.is_mobile    = state.points[key].is_mobile;
                state.overlay      = false;
            }
        });
    },
    setInspire(state, inspire) {
        state.inspire = inspire;
    },
    setStats(state, stats) {
        state.stats = stats;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
