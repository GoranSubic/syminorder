import Vue from 'vue';
import store from './store/index';

import MainImage from "./components/MainImage";
import Shopping from "./components/Shopping";
import Product from "./components/Product";
import OrdersTable from "./components/OrdersTable";
import OrdersTableUser from "./components/OrdersTableUser";
import ModalPlugin from 'bootstrap-vue';

Vue.use(ModalPlugin);

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#image',
    components: {
        store,
        MainImage,
        Shopping,
        Product,
        OrdersTable,
        OrdersTableUser
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
