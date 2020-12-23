import Vue from 'vue';
import store from './store/index';

import MainImage from "./components/MainImage";
import Shopping from "./components/Shopping";
import Product from "./components/Product";
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