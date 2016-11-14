require('./bootstrap');

var Turbolinks = require("turbolinks")
Turbolinks.start()

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

import myDatepicker from 'vue-datepicker';
Vue.component('date-picker', myDatepicker);

import DocumentIncident from './components/DocumentIncident.vue';
Vue.component('document-incident', DocumentIncident);


const app = new Vue({
    el: '#app'
});
