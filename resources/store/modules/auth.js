import Cookies from 'js-cookie'
import axios from 'axios';

const state = {
    authenticated: false,
    guest: false,
    token: '',
    user: null,
    subscriptionPlan: null,
    subscriptionStatus: null,
};

const getters = {
    guest: (state) => {
        return state.guest;
    },
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
    async checkGuest({ dispatch, state }, { route, http }) {
        if (state.guest && route.name !== 'GroceryStore') {
            await dispatch('signOutGuest', { http: http });
        }
    },
    async signInGuest({dispatch, state, commit}, { http }) {
        if (!state.authenticated || state.token === "") {
            Cookies.set('guest', true, { expires: 30 });
            await axios.get("/api/guest/login").then(({data}) => {
                if (data.success) {
                    commit('setSubscriptionStatus', data.subscription);
                    Cookies.set('token', data.token, {expires: 30});
                    http.defaults.headers.common['Authorization'] = "Bearer " + data.token;
                    commit('login', data.token);
                    commit('user', data.user);
                    commit('guest', true);
                } else {
                    Cookies.remove('guest');
                    window.location = '/login';
                }
            });
        }
        return true;
    },
    async signOutGuest({dispatch, state, commit}, { http }) {
        if (state.authenticated) {
            await http.post('/api/logout')
        }
        await Cookies.remove('token');
        http.defaults.headers.common['Authorization'] = "";
        await commit('logout');
        // await dispatch('stores/clearChoosedStores', null, { root: true });
        Cookies.remove('guest');
        commit('guest', false);
        return true;
    },
    async signIn({ dispatch, commit }, { http, token, user }) {
        Cookies.remove('guest');
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
        if (data.user) {
            commit('user', data);
        } else {
            commit('userInfo', data);
        }
    },
    async authenticate({ commit }, {data, http}) {
        Cookies.remove('guest');
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
    guest: (state, guest) => {
        state.guest = guest;
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
