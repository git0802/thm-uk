const state = {
    couponSets: null,
    selectedCouponEdit: null,
}

const getters = {
    getCouponSets(state) {
        return state.couponSets;
    },
    getSelectedCouponEdit(state) {
        return state.selectedCouponEdit;
    }
}

const actions = {
   async fetchSets({ commit }, http) {
       commit('adminUi/setIsLoading', true, {root: true})
        try {
            let res = await http.get(`/api/coupon`);

            commit('setCouponSets', res.data.sets);

        } catch (e) {
            console.log(e);
        }
       commit('adminUi/setIsLoading', false, {root: true})

   }
};

const mutations = {
    setCouponSets(state, sets) {
        state.couponSets = sets;
    },
    selectedCouponEdit(state, sets) {
        state.selectedCouponEdit = sets;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
