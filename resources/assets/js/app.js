require('./bootstrap');
require('leaflet');

import myDatepicker from 'vue-datepicker';
Vue.component('date-picker', myDatepicker);

import DocumentIncident from './components/DocumentIncident.vue';
Vue.component('document-incident', DocumentIncident);

const app = new Vue({
    el: '#app'
});
