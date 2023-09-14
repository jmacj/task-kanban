import './bootstrap';
import Vue from 'vue';
import vuetify from './plugins/vuetify';
import Registration from './vue/registration.vue';

new Vue({
    el: '#registration',
    render: h => h(Registration),
    vuetify
});
