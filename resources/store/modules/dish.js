import axios from "axios";
import Vue from 'vue';
const state = {
    isBusy: false,
    errors: [],
}

const getters = {
    getIsBusy: (state) => {
        return state.isBusy;
    },
    getErrors: (state) => {
        return state.errors;
    }
}

const mutations = {
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
    createDish: (context, dish) => {
        context.commit('setIsBusy', true);
        context.commit('clearErrors');
        const eating = dish.eating + 'Products';
        const product_ids = [ ...dish.products ];
        const meals_ids = [ ...dish.meals ];
        const formData = new FormData();

        //We need to do this for send array in formData
        meals_ids.forEach((item) => {
            formData.append('meal_ids[]', item);
        });

        formData.append("name", dish.name);
        formData.append("store_id", dish.store);
        formData.append("image", dish.image);

        axios.post('/api/dish/meals', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then((response) => {
                if (response.data.success) {
                    context.commit('food/clearChoosedFood', {}, { root: true })
                    context.dispatch('modals/actionsModal', {
                        name: 'modalDish',
                        action: 'close'
                    }, { root: true })

                    const stores = context.rootGetters["stores/getStores"];
                    const dish = response.data.dish;

                    stores.forEach((store) => {
                        if (dish.store_id === store.id) {
                            //Added object store with store name to product for correct work
                            dish.store = {
                                name: store.name
                            };
                        }
                    })
                    product_ids.forEach((item) => {
                        context.commit('food/deleteFoodId', item, {root:true});
                    });
                    meals_ids.forEach((item) => {
                        context.commit('food/deleteFoodIdChoosed', item, {root:true});
                        context.commit('food/cancelChooseMeal', item, {root:true});
                    });
                    context.dispatch('planner/fetchWeekPlan', null, {root:true})
                    // let productDish = {
                    //     product: {
                    //         dish
                    //     }
                    // }
                    // context.commit('planner/addToEating', {eating: eating, data: productDish}, {root: true})
                    // meals_ids.forEach((item) => {
                    //     context.commit('planner/deleteFromEatingsArray', {eating: eating, id: item}, { root: true })
                    // });

                    // context.commit('food/addFood', dish, { root: true })
                }
            })
            .catch((error) => {
                const errorMessage = error.response.data.message;
                context.dispatch('modals/actionsModal', {
                    name: 'modalDish',
                    action: 'close'
                }, { root: true })
                Vue.notify({
                    group: 'planner',
                    title: `Error!`,
                    type: 'error',
                    text: errorMessage,
                    duration: 7500
                });
                context.commit('addError', errorMessage);
            })
            .then((response) => {
                context.commit('setIsBusy', false);
            })
    },

    clearMessages: (context) => {
        context.commit('clearErrors');
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
