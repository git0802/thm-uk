import Axios from 'axios';
import store from'../store';


const http = {
    install(Vue, options) {
        Vue.prototype.$http = Axios;
        /**
         *  if we receive 401 - we making logout and redirect to login page
         *  if we receive 307 - we need to redirect to .data.route (not from Location header)
         */
        Vue.prototype.$http.interceptors.response.use(function(response) {
            return response;
        }, async function(error) {
            switch (error.response.status) {
                case 401:
                    store.dispatch('auth/signOut', Vue.prototype.$http)
                        .then(e => {
                            console.log(e);
                        });
                    return Promise.reject(error);
                    break;
                case 307:
                    window.location = error.response.data.route;
                    break;

                case 402:
                    if (!window.blockSubscriptionNotification) {
                        window.blockSubscriptionNotification = true
                        Vue.notify({
                            group: 'planner',
                            title: `Subscription! ⚠️`,
                            type: 'error',
                            text: error.response.data.message,
                            duration: 7500
                        })

                        setTimeout(function() {
                            window.blockSubscriptionNotification = false
                        }, 7500)
                    }

                    return Promise.reject(error);
                    break
                default:
                    return Promise.reject(error);
                    break;
            }
        });
    }
}

export default http;
