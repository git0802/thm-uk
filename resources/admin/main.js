import bootstrap from "./plugins/bootstrap";
import Vue from 'vue';
import App from "./App";
import router from "./router";
import vuetify from './plugins/vuetify';
import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use( CKEditor );

/** Common */
import common from '../prototypes/common'
Vue.use(common);

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


import Cookies from 'js-cookie';

router.beforeEach(async (to, from, next) => {
    let token = Cookies.get('token')
    if(store.getters['auth/authenticated'] && token) {
        store.commit('adminUi/setIsLoading', true)

        let isValid = await store.dispatch('auth/checkToken', token);
        store.commit('adminUi/setIsLoading', false)

        if (isValid.authenticated && (typeof (isValid.user) !== 'undefined' ? isValid.user.is_admin : false)) {
            await store.dispatch('auth/signIn', {
                http: Vue.prototype.$http,
                token: token,
                user: isValid.user
            });
            await next();
        } else {
            await store.dispatch('auth/signOut', Vue.prototype.$http);
        }
    } else {
        await store.dispatch('auth/signOut', Vue.prototype.$http);
    }
})


const files = require.context('./components/', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify,
    render: h => h(App)
});
