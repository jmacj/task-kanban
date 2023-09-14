import './bootstrap';
import Vue from 'vue';
import vuetify from './plugins/vuetify';
import Login from './vue/login.vue';


new Vue({
    el: '#login',
    render: h => h(Login),
    vuetify
});
