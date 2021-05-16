import axios from "axios";

const state = {
    stores: [],
    choosedStores: [],
    selectedStore: {
        id: 0,
        image: '',
        is_custom: false,
        name: ''
    },
    isBusy: false,
    errors: [],
    successMessage: ''
}

const getters = {
    getChoosedStores: (state) => {
        return state.choosedStores;
    },
    getStores: (state) => {
        return state.stores;
    },
    getIsBusy: (state) => {
        return state.isBusy;
    },
    getErrors: (state) => {
        return state.errors;
    },
    getSelectedStore: (state) => {
        return state.selectedStore;
    },
    getSuccessMessage: (state) => {
        return state.successMessage;
    },
}

const mutations = {
    choose: (state, value) => {
        state.choosedStores.unshift(value);
    },
    choosePush: (state, value) => {
        state.choosedStores.push(value);
    },
    cancelChoose: (state, id) => {
        let index = state.choosedStores.findIndex(item => item.id == id)
        state.choosedStores.splice(index, 1)
    },
    clearAllChoosedStore(state){
        state.choosedStores = []
    },
    setIsBusy: (state, value) => {
        state.isBusy = value;
    },
    refreshStoresList: (state, stores) => {
        state.stores = [ ... stores];
    },
    clearStoresList: (state) => {
        state.stores.length = 0;
    },
    addNewStore: (state, store) => {
        state.stores.push(store);
    },
    addNewStoreAtStart: (state, store) => {
        state.stores.unshift(store);
    },
    addError: (state, value) => {
        state.errors.push(value);
    },
    clearErrors: (state) => {
        state.errors = [];
    },
    setSelectedStore: (state, store) => {
        state.selectedStore = { ...store };
    },
    setSuccessMessage: (state, message) => {
        state.successMessage = message;
    },
}

const actions = {
    clearChoosedStores(context){
        context.commit('clearAllChoosedStore');
    },
    async getStoresList(context) {
        context.commit('setIsBusy', true);
        try {
            let res = await axios.get('/api/store')
            context.commit('clearStoresList');
            context.commit('refreshStoresList', res.data.data);
            context.commit('addNewStore', {name: '+ Add your store', id: 0})
            context.commit('setIsBusy', false);
        } catch (e) {
            context.commit('setIsBusy', false);

        }
    },
    async deleteStore(context, id) {
        if (confirm('Are you sure that you want to delete this store?')) {
            context.commit('setIsBusy', true);
            try {
                const response = await axios.delete(`/api/store/soften/` + id);
                await context.dispatch('getStoresList');
                return response.data;
            } catch (e) {
            }
            context.commit('setIsBusy', false);
        }
    },

    async getDefaultStoresList(context) {
    context.commit('setIsBusy', true);
    try {
        let res = await axios.get('/api/store/defaults')
        context.commit('clearStoresList');
        context.commit('refreshStoresList', res.data.data);
        context.commit('setIsBusy', false);
    } catch (e) {
        context.commit('setIsBusy', false);
    }
},

    createStore: (context, store) => {
        context.commit('setIsBusy', true);
        context.commit('clearErrors');
        context.commit('setSuccessMessage', '');

        const formData = new FormData();
        formData.append("name", store.name);
        formData.append("image", store.image);

        axios.post('/api/store', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then((response) => {
                if (response.data.success) {
                    context.commit('setSuccessMessage', response.data.message);
                    context.commit('addNewStoreAtStart', response.data.store);
                    context.dispatch('getStoresList');
                    context.dispatch('modals/actionsModal', {
                        name: 'modalStore',
                        action: 'close'
                    }, { root: true })
                }
            })
            .catch((error) => {
                const errorMessage = error.response.data.errors.name[0];

                context.commit('addError', errorMessage);
            })
            .then((response) => {
                context.commit('setIsBusy', false);
            })
    },
    clearMessages: (context) => {
        context.commit('clearErrors');
        context.commit('setSuccessMessage', '');
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
