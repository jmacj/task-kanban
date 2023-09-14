require('./bootstrap');

import Vue from 'vue';
import App from './vue/app.vue';
import vuetify from './plugins/vuetify';
import router from './router';
new Vue({
    el: '#app',
    render: h => h(App),
    router,
    vuetify
});
