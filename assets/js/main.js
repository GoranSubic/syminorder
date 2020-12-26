import Vue from 'vue';
import store from './store/index';

import MainImage from "./components/MainImage";
import Shopping from "./components/Shopping";
import Product from "./components/Product";
import ModalPlugin from 'bootstrap-vue';

/*import library from '@fortawesome/fontawesome-svg-core';
import faUser from '@fortawesome/free-solid-svg-icons';
import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
library.add(faUser);
Vue.component('font-awesome-icon', FontAwesomeIcon);*/

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
    localStorage.setItem('store', JSON.stringify(state));
});
