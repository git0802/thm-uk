import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate"

// modules
import adminUi from './modules/adminUi'
import auth from './modules/auth'
import coupon from './modules/coupon'
import publicUi from "./modules/publicUi"

// THIS FAILING
import info from './modules/info'
import modals from './modules/modals'
import params from './modules/params'
import food from './modules/food'
import dish from './modules/dish'
import planner from './modules/planner'
import plannerUi from './modules/plannerUi'
import signIn from './modules/signIn'
import snackbar from './modules/snackbar'
import stores from './modules/stores'
import loader from './modules/loader'


Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        loader,
        adminUi,
        auth,
        coupon,
        // THIS FAILING
        info,
        modals,
        params,
        food,
        dish,
        planner,
        plannerUi,
        signIn,
        snackbar,
        stores,
        publicUi
    },
    strict: debug,
    plugins: [createPersistedState({
        paths: [
            'ui.right_drawer', 'auth',  'stores.choosedStores', 'publicUi.gdpr', 'stores.selectedStore'
        ],
        key: "thehotmeal"
    })]
})
