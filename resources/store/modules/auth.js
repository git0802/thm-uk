import Cookies from 'js-cookie'
import axios from 'axios';

const state = {
    authenticated: false,
    token: '',
    user: null,
    subscriptionPlan: null,
    subscriptionStatus: null,
};

const getters = {
    authenticated: (state) => {
        return state.authenticated;
    },
    token: (state) => {
        return "Bearer " + state.token;
    },
    user: (state) => {
        return state.user;
    },
    getSubscriptionPlan: (state) => {
        return state.subscriptionPlan;
    },
    getSubscriptionStatus: (state) => {
        return state.subscriptionStatus;
    },
};

const actions = {
    check({ state, commit }) {
        commit('adminUi/setIsLoading', true, {root: true});
        if (state.authenticated || state.token !== "") {
            axios.get(`/api/subscription/status`).then(({data}) => {
                commit('setSubscriptionStatus', data.data);
            });
            axios.get("/api/check")
                .then(({data}) => {
                    if(data.authenticated === true){
                        commit("user", data);
                    }
                    else{
                        commit("logout")
                    }
                })
                .catch(() => {
                    commit("logout")
                })
        }
        commit('adminUi/setIsLoading', false, {root: true})

    },
    async signIn({ dispatch, commit }, { http, token, user }) {
        Cookies.set('token', token, { expires: 30 });
        http.defaults.headers.common['Authorization'] = "Bearer " + token;
        await commit('login', token);
        await commit('user', user);
        await dispatch('checkSubscriptionStatus');
        return true;
    },
    async refreshSession({ dispatch, commit }, { http, token, user }) {
        http.defaults.headers.common['Authorization'] = "Bearer " + token;
        await commit('login', token);
        await commit('user', user);
        await dispatch('checkSubscriptionStatus');

    },
    async signOut(context, http) {
        if(state.authenticated) {
            await http.post('/api/logout')
        }
        await Cookies.remove('token');
        http.defaults.headers.common['Authorization'] = "";
        await context.commit('logout');
        context.dispatch('stores/clearChoosedStores', null, { root: true });
        window.location = window.location.origin + '/login';
        return true;
    },
    async checkToken({ commit }, token) {
        let res = await axios.get(`/api/check`, {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
        return res.data
    },
    async checkSubscriptionStatus({ state, commit }) {
        if(state.authenticated){
            const res = await axios.get(`/api/subscription/status`);
            commit('setSubscriptionStatus', res.data.data);
            return res.data.data;
        }
    },
    async storeUserData({ commit }, data) {
        commit('user', data);
    },
    async authenticate({ commit }, {data, http}) {
        return await http.post('/api/login', {
            email: data.email,
            password: data.password,
        })
    }
};

const mutations = {
    login: (state, token) => {
        state.token = token;
        state.authenticated = true
    },
    logout: (state) => {
        state.token = "";
        state.authenticated = false;
        state.user = null;
        Cookies.remove('token')
    },
    user: (state, user) => {
        state.user = user.user;
        state.subscriptionPlan = user.subscription;
    },
    userInfo: (state, user) => {
        state.user = user;
    },
    setUserWeight: (state, weight) => {
        state.user.weight = weight;
    },
    setSubscriptionPlan: (state, plan) => {
        state.subscriptionPlan = plan;
    },
    setSubscriptionStatus: (state, substatus) => {
        state.subscriptionStatus = substatus;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
