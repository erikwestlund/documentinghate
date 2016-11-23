require('./bootstrap');
require('leaflet');

// require('marked');

import myDatepicker from 'vue-datepicker';
Vue.component('date-picker', myDatepicker);

import Modal from './components/Modal.vue';
Vue.component('modal', Modal);

import DocumentIncident from './components/DocumentIncident.vue';
Vue.component('document-incident', DocumentIncident);

const app = new Vue({
    el: '#app'
});
