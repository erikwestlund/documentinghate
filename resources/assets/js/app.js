require('./bootstrap');

require('form-forker/dist/jquery.form-forker.min.js');

var VueTables = require('vue-tables-2');

Vue.use(VueTables.server, {
    delay: 125,
    compileTemplates: true,
    perPage: 25,
    texts: {
        filter: '',
        filterPlaceholder: 'Filter results...'
    },
    sortIcon: {
        base:'fa',
        up:'fa-chevron-up',
        down:'fa-chevron-down'
    },
    skin: 'table-hover'
});


var Turbolinks = require("turbolinks")
Turbolinks.start()

import myDatepicker from 'vue-datepicker';
Vue.component('date-picker', myDatepicker);

import DocumentIncident from './components/DocumentIncident.vue';
Vue.component('document-incident', DocumentIncident);

import AdminIncidentTable from './components/AdminIncidentTable.vue';
Vue.component('admin-incident-table', AdminIncidentTable);

const app = new Vue({
    el: '#app'
});
