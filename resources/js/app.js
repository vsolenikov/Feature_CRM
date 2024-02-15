require('./bootstrap');
import VModal from "vue-js-modal";


window.Vue = require('vue');

Vue.use(require('vue-resource'));
Vue.use(VModal);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('approve-table', require('./components/approve/table.vue').default);
Vue.component('approve-categories-attach', require('./components/settings/ApproveCategoriesAttach.vue').default);
Vue.component('deliveryform', require('./components/approve/deliveryform.vue').default);
Vue.component('contacts-selector', require('./components/requests/ContactsSelector.vue').default);
Vue.component('InfiniteLoading', require('vue-infinite-loading'));
Vue.component('Contentplites', require('./components/wiki/Contentplites.vue').default);


const app = new Vue({
    el: '#app',
});