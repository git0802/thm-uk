import axios from "axios";

const state = {
    is_mobile: false,
    drawer: null,
    couponModal: false,
    site_content: null,
    blogModal: false,
    interceptedBlogModal: false,
    storeModal: false,
    pages: [],
    isLoading: false
};

const getters = {
    getIsLoading(state) {
        return state.isLoading
    },
    getSiteContent(state) {
        return state.site_content
    },
    getDrawer(state) {
        return state.drawer;
    },
    getCouponModal(state) {
        return state.couponModal;
    },
    getBlogModal(state) {
        return state.blogModal;
    },
    getInterceptedBlogModal(state) {
        return state.interceptedBlogModal
    },
    getStoreModal(state) {
        return state.storeModal
    },
    getPages(state) {
        return state.pages;
    }
};

const actions = {
    fetchGetContent({ commit }) {
        axios.get(`/api/content`)
        .then((res) => {
          commit('setSiteContent', res.data.data)
            })
       .catch((e) => {
       })
    },
    fetchUpdateContent({ commit }, data) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/content/update/` + data.id , {
                block_name: data.block_name,
                title: data.title,
                description: data.description,
            })
                .then(response => {
                    resolve(response.data)
                }).catch(error => {
                reject(error)
            })
        })
    },
    async fetchPages({ commit }, http) {
        try {
            let res = await http.get(`/api/page?order=1`);

            commit('setPages', res.data.data);
        } catch (e) {
            if(e.response.data.errors) {
                Object.keys(e.response.data.errors).forEach(i => {
                    commit('snackbar/pushMessage', {
                        message: e.response.data.errors[i],
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                })
            } else if (e.response.data.message) {
                e.response.data.message.forEach(m => {
                    commit('snackbar/pushMessage', {
                        message: m,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                })
            }
        }
    },
};

const mutations = {
    setIsLoading(state, payload) {
        state.isLoading = payload
    },
    clearContent(state) {
        state.site_content = []
    },
    setSiteContent(state, payload) {
        state.site_content = payload
    },
    setDrawer(state, drawerState) {
        state.drawer = drawerState;
    },
    setCouponModal(state, modalState) {
        state.couponModal = modalState
    },
    setBlogModal(state, modalState) {
        state.blogModal = modalState
    },
    setInterceptedBlogModal(state, intercepted) {
        state.interceptedBlogModal = intercepted
    },
    setStoreModal(state, modalState) {
        state.storeModal = modalState
    },
    setPages(state, pages) {
        state.pages = [];
        state.pages = [ ...pages];
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
