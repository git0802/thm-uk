import Vue from 'vue'
import Vuetify from 'vuetify'
import "@mdi/font/css/materialdesignicons.css";
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    theme: {
        custom: true,
        themes: {
            custom: {
                primary: '#e91e63',
                secondary: '#673ab7',
                accent: '#3f51b5',
                error: '#f44336',
                warning: '#ffc107',
                info: '#03a9f4',
                success: '#4caf50'
            }
        }
    }
};

export default new Vuetify(opts)

