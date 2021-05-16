const state = {
    activeModals: []
}

const getters = {
    getActiveModals: (state) => {
        return state.activeModals;
    },
}

const mutations = {
    openModal: (state, modal) => {
        state.activeModals.push(modal);
    },
    closeModal: (state, modal) => {
        const index = state.activeModals.findIndex((item) => {
            return item.name === modal.name;
        });

        if (index !== -1) {
            state.activeModals.splice(index, 1);
        }
    },
}

const actions = {
    actionsModal: (context, modal) => {
        const BASE_Z_INDEX = 20;

        if (modal.action === 'open') {
            const modals = state.activeModals;

            context.commit('openModal',{
                name: modal.name,
                zIndex: modals.length ? modals[modals.length - 1].zIndex + 1 : BASE_Z_INDEX
            })
        } else if (modal.action === 'close') {
            context.commit('closeModal', modal);
        }
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
