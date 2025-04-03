import IndexField from './components/IndexField.vue';
import DetailField from './components/DetailField.vue';
import FormField from './components/FormField.vue';

Nova.booting((Vue, router) => {
    Vue.component('index-nova-gmap', IndexField);
    Vue.component('detail-nova-gmap', DetailField);
    Vue.component('form-nova-gmap', FormField);
})
