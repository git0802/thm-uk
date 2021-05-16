import axios from 'axios';
import Cookies from 'js-cookie'

const state = {
    signInParams: {
        email: '',
        password: ''
    },
    user: {
        age: 0,
        created: '',
        email: '',
        gender: '',
        height: 0,
        name: '',
        last_name: '',
        phone: '',
        weight: 0
    },
    isBusy: false,
    errors: []
}

const getters = {
    getSignInParams: (state) => {
        return state.signInParams;
    },
    getIsBusy: (state) => {
        return state.isBusy;
    },
    getErrors: (state) => {
        return state.errors;
    },
    getUser: (state) => {
        return state.user;
    },
}

const mutations = {
    setSignInParam: (state, param) => {
        state.signInParams[param.key] = param.value;
    },
    setUserParams: (state, user) => {
        state.user = { ...user };
    },
    setIsBusy: (state, value) => {
        state.isBusy = value;
    },
    addError: (state, value) => {
        state.errors.push(value);
    },
    clearErrors: (state) => {
        state.errors = [];
    }
}

const actions = {
    login: (context) => {
        context.commit('setIsBusy', true);
        context.commit('clearErrors');

        axios.post('/api/login', {
            email: context.state.signInParams.email,
            password: context.state.signInParams.password
        })
            .then((response) => {
                if (response.data.success) {
                    Cookies.set('token', response.data.token, { expires: 30 });
                    context.commit('setUserParams', response.data.user);
                    window.location = '/meal-planner/setup';
                } else {
                    const errorMessage = response.data.message;

                    context.commit('addError', errorMessage);
                }
            })
            .catch((error) => {
                const errorMessage = error.response.data.message;

                context.commit('addError', errorMessage);
            })
            .then((response) => {
                context.commit('setIsBusy', false);
            })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
