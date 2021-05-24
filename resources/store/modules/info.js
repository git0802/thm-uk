import axios from "axios";

const state = {
    couponNumber: '',
    isValidUserForm: true,
    isBusy: false,
    errors: [],

    errorsRegister: [],
    discount: {
        success: false,
        message: '',
        set: {
            type: '',
            value: 0
        }
    },
    subscription: 0,
    price: [],
    successMessage: '',
    verification: false,
    checkout: null,
    userData: null
}

const getters = {
    getIsBusy: (state) => {
        return state.isBusy;
    },
    getIsValidUserForm: (state) => {
        return state.isValidUserForm;
    },
    getErrors: (state) => {
        return state.errors;
    },
    getErrorsRegister: (state) => {
        return state.errorsRegister;
    },
    getCouponNumber: (state) => {
        return state.couponNumber;
    },
    getDiscount: (state) => {
        return state.discount;
    },
    getSubscription: (state) => {
        return state.subscription;
    },
    getPrice: (state) => {
        return state.price;
    },
    getSuccessMessage: (state) => {
        return state.successMessage;
    },
    getCheckout: (state) => {
        return state.checkout;
    },
    getUserData: (state) => {
        return state.userData;
    },
    getSubscriptionPlan: (state) => {
        for(let i = 0; i < state.price.length; i++){
            if(state.price[i].id == state.subscription){
                return state.price[i];
            }
        }
        return state.price[0];
    }
};

const mutations = {
    setCouponNumber: (state, value) => {
        state.couponNumber = value;
    },
    setIsValidUserForm: (state, value) => {
        state.isValidUserForm = value;
    },
    setIsBusy: (state, value) => {
        state.isBusy = value;
    },
    setPrice: (state, value) => {
        state.price = value;
    },
    addError: (state, value) => {
        state.errors.push(value);
    },
    addErrorRegister: (state, value) => {
        state.errorsRegister.push(value);
    },
    clearErrors: (state) => {
        state.errors = [];
    },
    clearErrorsRegister: (state) => {
        state.errorsRegister = [];
    },
    setDiscount(state, discount) {
        state.discount = { ...discount };
    },
    setSubscription(state, subscription) {
        state.subscription = subscription;
    },
    deleteDiscount(state, defaultDiscount) {
        state.discount = { ...defaultDiscount };
    },
    setSuccessMessage(state, value) {
        state.successMessage = value;
    },
    setVerification(state, value) {
        state.verification = value;
    },
    setCheckout: (state, value) => {
        state.checkout = value;
    },
    setUserData: (state, value) => {
        state.userData = value;
    }
};

const actions = {
    async getPrice(context) {
        context.commit('setIsBusy', true);
        try {
            let res = await axios.get("/api/get/price/");
            context.commit('setPrice', res.data);
            if(context.state.subscription  === 0){
                context.commit('setSubscription', res.data[res.data.length-1].id);
            }
        } catch (e) {
            console.log(e);
        }
        context.commit('setIsBusy', false);
    },
    async checkCoupon(context) {
        context.commit('setIsBusy', true);
        context.commit('clearErrors');
        const couponCode = context.getters.getCouponNumber;
        try {
            let res = await axios.post(`/api/coupon/check`, {
                code: couponCode
            });
            context.commit('setDiscount', res.data);
        } catch (e) {
            const errorMessage = e.response.data.message;
            context.commit('addError', errorMessage);
        }
        context.commit('setIsBusy', false);
    },
    register: (context, payload) => {
        let action = context.state.verification ? '/api/register' : '/api/check-cart',
            finaly = true;

        context.commit('setIsBusy', true);
        context.commit('clearErrorsRegister');

        payload.userInfo.verification = context.state.verification;

        axios.post(action, payload.userInfo)
            .then((response) => {
                switch (response.data.result) {
                    case 'succeeded':
                        finaly = false;

                        context.commit('setVerification', response.data.id);
                        actions.register(context, payload);

                        break;
                    case 'success':
                        context.commit('setSuccessMessage', response.data.message);
                        context.commit('setVerification', null);
                        payload.router.push({ name: 'Check' });

                        break;
                    case 'requires_action':
                        context.commit('setCheckout', response.data.action.redirect_to_url.url);
                        context.commit('setVerification', response.data.id);
                        context.commit('setUserData', payload.userInfo);

                        break;
                    case 'requires_payment_method':
                        context.commit('addErrorRegister', 'It\'s impossible to connect this card to your account. Please use another card and try again.');

                        break;
                }
            })
            .catch((error) => {
                console.log(error)
                context.commit('setVerification', null);
                const errors = error.response.data.errors;

                if (errors) {
                    Object.keys(errors).forEach((k) => {
                        errors[k].forEach((e) => {
                            context.commit('addErrorRegister', e);
                        })
                    })
                } else {
                    const message = error.response.data.message;
                    context.commit('addErrorRegister', message);
                }
            })
            .then(() => {
                if (finaly) {
                    context.commit('setIsBusy', false);
                }
            })
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
