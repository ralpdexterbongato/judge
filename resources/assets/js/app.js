
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('setupindex', require('./components/Setup/index.vue'));
Vue.component('eventcreate', require('./components/Event/CreateEvent.vue'));
Vue.component('eventindex', require('./components/Event/index.vue'));
Vue.component('ratingcreate', require('./components/Rating/create.vue'));
Vue.component('accountindex', require('./components/Account/index.vue'));
Vue.component('createactivity', require('./components/Setup/createActivity.vue'));
Vue.component('registerform', require('./components/Account/Register.vue'));
Vue.component('results', require('./components/Results/Result.vue'));
const app = new Vue({
    el: '#app'
});
