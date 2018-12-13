import Vue from 'vue';
import App from './App.vue';
import VueAxios from 'vue-axios';
import {router, config, http} from './helpers';
import {store} from './store';
import './registerServiceWorker';
import i18n from './i18n';

Vue.config.productionTip = false;

Vue.use(VueAxios, http);

// eslint-disable-next-line
new Vue({
    el: '#app',
    router,
    store,
    i18n,
    config,
    render: h => h(App)
});
