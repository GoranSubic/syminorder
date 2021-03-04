import Vue from 'vue';
import store from './store/index';

import MainImage from "./components/MainImage";
import MainCarousel from "./components/MainCarousel";
import Shopping from "./components/Shopping";
import ProductButtons from "./components/ProductButtons";
import OrdersTable from "./components/OrdersTable";
import OrdersTableUser from "./components/OrdersTableUser";
import CategoriesMain from "./components/CategoriesMain";
import CategorySelected from "./components/CategorySelected";
import ModalPlugin from 'bootstrap-vue';
import Multiselect from 'vue-multiselect';

// register globally
Vue.component('multiselect', Multiselect)
Vue.use(ModalPlugin);

// Load the Translator from the composer version
export const Translator = require('../../vendor/willdurand/js-translation-bundle/Resources/js/translator');
// Make it global
global.Translator = Translator;
// Load the default config
require('./bazinga/translations/config');
// Load each language here. When we add more languages, we need to add them here.
require('./bazinga/translations/sr');

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#image',
    components: {
        store,
        MainImage,
        MainCarousel,
        Shopping,
        ProductButtons,
        OrdersTable,
        OrdersTableUser,
        CategoriesMain,
        CategorySelected,
    },
    data() {
        return {
            dataUname: '',
        }
    },
    beforeMount: function() {
        this.dataUname = this.uname;
    },
    computed: {
        uname: function () {
            var isAuthenticated = this.$el.attributes['data-is-authenticated'].value;
            var userName = '';//'guest';
            if (isAuthenticated) {
                userName = this.$el.attributes['data-user-name'].value;
            }
            return userName;
        }
    }
});

// Subscribe to store updates
store.subscribe((mutation, state) => {
    // Store the state object as a JSON string
    localStorage.setItem('store-olala', JSON.stringify(state));
});
