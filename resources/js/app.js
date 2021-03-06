/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueTheMask from 'vue-the-mask'
import vueMask from 'vue-jquery-mask';
import Vuex from 'vuex';
import Notifications from 'vue-notification'
import ru from 'vee-validate/dist/locale/ru';


import VeeValidate, { Validator } from 'vee-validate';


Vue.use(VeeValidate);
Validator.localize('ru', ru);

window.Vue.use(VueTheMask);

window.Vue.use(vueMask);

window.Vue.use(Vuex);

window.Vue.use(Notifications);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


window.Event = new Vue();
