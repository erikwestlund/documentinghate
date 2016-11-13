require('./bootstrap');

var Turbolinks = require("turbolinks")
Turbolinks.start()


import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

import DocumentIncident from './components/DocumentIncident.vue';
import myDatepicker from 'vue-datepicker';
Vue.component('date-picker', myDatepicker);


const app = new Vue({
    el: '#app',
    components: {
        DocumentIncident
    }
});
