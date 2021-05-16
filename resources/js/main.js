import bootstrap from "./plugins/bootstrap";
import Vue from 'vue';
import App from "./App";
import router from "./router";
import Vue2TouchEvents from 'vue2-touch-events'
import smoothscroll from 'smoothscroll-polyfill';
import vClickOutside from 'v-click-outside';
import VueMeta from 'vue-meta'
import Notifications from 'vue-notification'

Vue.use(vClickOutside)
Vue.use(Vue2TouchEvents);
Vue.use(VueMeta);
Vue.use(Notifications)

/** Common */
import common from '../prototypes/common'
Vue.use(common);

/**
 * tooltip for drag-drop
 */

import VTooltip from 'v-tooltip'
Vue.use(VTooltip);

/**
 * store not in this bundle!
 */
import store from '../store'


/**
 *
 * Axios wrapper. Do all call to server by this! By $http
 *
 */

import http from '../prototypes/http'
Vue.use(http);



bootstrap();
smoothscroll.polyfill();

const files = require.context('./components/', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});
