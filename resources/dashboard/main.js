import bootstrap from "./plugins/bootstrap"
import Vue from 'vue'
import App from "./App"
import router from "./router"
import Vue2TouchEvents from 'vue2-touch-events'
import VCalendar from 'v-calendar'
import VueTour from 'vue-tour'
import vClickOutside from 'v-click-outside'
import Notifications from 'vue-notification'

import startCase from 'lodash/startCase'

/** Common */
import common from '../prototypes/common'
Vue.use(common);

//
/**
 * store not in this bundle!
 */
import store from '../store'


/**
 * For tutorial button in main meal-planner page
 */

require('vue-tour/dist/vue-tour.css')
Vue.use(VueTour)

/**
 * tooltip for drag-drop
 */

import VTooltip from 'v-tooltip'
Vue.use(VTooltip);

Vue.use(vClickOutside)
Vue.use(VCalendar, {
    componentPrefix: 'vc',
})
Vue.use(Vue2TouchEvents);


Vue.use(VCalendar, {
    componentPrefix: 'vc',  // Use <vc-calendar /> instead of <v-calendar />
});

Vue.use(Notifications)


/**
 *
 * Axios wrapper. Do all call to server by this! By $http
 *
 */

import http from '../prototypes/http'
Vue.use(http);

/**
 * todo: move to prototype
 */

Vue.directive('case-hide', {
    bind: function (el, binding, vnode) {
        var s = JSON.stringify;
        vnode.context.$nextTick(() => {
            let parent = vnode.elm.parentElement
            let sizeCase = binding.value.case
            let breakPoint = binding.value.break

            // if(parent[`offset` + _.startCase(sizeCase)] < breakPoint) {
                if(parent[`offset` + startCase(sizeCase)] < breakPoint) {
                el.style.display = 'none';
            }
        });

    },
    update: function (el, binding, vnode) {
        var s = JSON.stringify;
        vnode.context.$nextTick(() => {
            let parent = vnode.elm.parentElement
            let sizeCase = binding.value.case
            let breakPoint = binding.value.break

            if(parent[`offset` + startCase(sizeCase)] < breakPoint) {
                // if(parent[`offset` + _.startCase(sizeCase)] < breakPoint) {
                el.style.display = 'none';
            }
        });
    },


});


/**
 * todo: do todo
 */

bootstrap();

/**
 *
 * Auto import for all .vue files
 *
 */

const files = require.context('./components/', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * We fetching new inspire (right drawer block) each time. i know.
 */


import Cookies from 'js-cookie';
let checked = false;
router.beforeEach(async (to, from, next) => {
    let token = Cookies.get('token')
    if(store.getters['auth/authenticated'] && token && !checked) {
        let isValid = await store.dispatch('auth/checkToken', token);
        if (isValid.authenticated) {
            await store.dispatch('auth/refreshSession', {
                http: Vue.prototype.$http,
                token: token,
                user: isValid
            });
            checked = true;
            await next();
        } else {
            await store.dispatch('auth/signOut', Vue.prototype.$http);
        }
    } else {
        next();
    }
})

router.beforeEach(async (to, from, next) => {
    store.dispatch('plannerUi/fetchInspire', Vue.prototype.$http);
    store.dispatch('plannerUi/fetchStats', Vue.prototype.$http);
    next();
})

const app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});

