import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        products: [],
        StoreCart: [],
        storeCreated: new Date(),
        storeCartNote: String,
        storeCartCity: String,
        storeCartAddress: String,
        storeCartPhone: String,
    },
    getters: {
        products: (state) => state.products,
        StoreCart: (state) => state.StoreCart,
        storeCreated: (state) => state.storeCreated,
        storeCartNote: (state) => state.storeCartNote,
        storeCartCity: (state) => state.storeCartCity,
        storeCartAddress: (state) => state.storeCartAddress,
        storeCartPhone: (state) => state.storeCartPhone,
    },
    mutations: {
        CHECK_And_Clear_Store(state, init) {
            //check if store is older than 1 hour
            var now = new Date();
            var storeDate = new Date(state.storeCreated);
            var diff = parseInt(now - storeDate)/60000;
            if(diff > 60 || (typeof init !== 'undefined' && init === 'restart')) {
                //reset localStorage and state
                localStorage.removeItem('store-olala');
                state.products = [];
                state.StoreCart = [];
                state.storeCreated = new Date();
                state.storeCartNote = '';
                state.storeCartCity = '';
                state.storeCartAddress = '';
                state.storeCartPhone = '';
                //reload page after add, remove item if store older than 1 hour and after submit form
                if(typeof init === 'undefined' || init !== 'init' || init === 'restart') location.reload();
            }
        },
        Initialise_Store(state) {
            var createdDate = state.storeCreated;
            // Check if the ID exists
            if(localStorage.getItem('store-olala') && createdDate) {
                // Replace the state object with the stored item
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('store-olala')))
                );
            } else {
                state.storeCreated = new Date();
            }
        },
        ADD_Item(state, product) {
            // var item = state.products.find(productItem => (productItem.id === product.id));
            var items = state.products.filter(productItem => (productItem.id === product.id));
            var item = items.length
                ? items.find(productItem => (productItem.addcode === product.addcode)) : null;

            if(typeof item !== 'undefined' && item !== null &&
                typeof item.ammount !== 'undefined' && item.ammount > 0) {
                    item.ammount += 1;
            } else {
                product.ammount = 1;
                state.products.push(product);
            }
            if (typeof product.addcode !== 'undefined' && product.addcode !== '') {
                state.StoreCart.push(product.addcode);
            } else {
                state.StoreCart.push(product.id);
            }
        },

        /*REMOVE_Item(state, index) {
            state.StoreCart.splice(index, 1);
        },*/

        REMOVE_ItemById(localStorage, id) {
            localStorage.products = localStorage.products.filter(product => (
                product.id !== id
            ));
            localStorage.StoreCart = localStorage.StoreCart.filter(storeId => (storeId !== id));
        },

        REMOVE_ItemByAddcode(localStorage, addcode) {
            localStorage.products = localStorage.products.filter(product => (
                product.addcode !== addcode
            ));
            localStorage.StoreCart = localStorage.StoreCart.filter(storeId => (storeId !== addcode));
        },

        REMOVE_One_Item(state, id) {
            var item = state.products.find(productItem => (productItem.id === id));
            if(typeof item === 'undefined') return;

            if(typeof item.ammount !== 'undefined' && item.ammount === 1) {
                state.products = state.products.filter(product => (product.id !== id));
                state.StoreCart = state.StoreCart.filter(storeId => (storeId !== id));
            } else if(typeof item.ammount !== 'undefined' && item.ammount > 1){
                item.ammount--;

                var index = state.StoreCart.indexOf(id);
                if (index !== -1) {
                    state.StoreCart.splice(index, 1);
                }
            }
        },

        SET_note(state, txt) {
            state.storeCartNote = txt;
        },

        SET_city(state, txt) {
            state.storeCartCity = txt;
        },

        SET_address(state, txt) {
            state.storeCartAddress = txt;
        },

        SET_phone(state, txt) {
            state.storeCartPhone = txt;
        },
    },
    actions: {
        initialiseStore(context) {
            context.commit("Initialise_Store");
            context.commit("CHECK_And_Clear_Store", "init");
        },

        clearStore(context) {
            context.commit("CHECK_And_Clear_Store", "restart");
        },

        addItem(context, product) {
            context.commit("CHECK_And_Clear_Store");
            context.commit("ADD_Item", product);
        },

        removeItem(context, id) {
            context.commit("CHECK_And_Clear_Store");
            context.commit("REMOVE_ItemById", id);
        },

        removeItemByAddcode(context, addcode) {
            context.commit("CHECK_And_Clear_Store");
            context.commit("REMOVE_ItemByAddcode", addcode);
        },

        decreaseItem(context, id) {
            context.commit("CHECK_And_Clear_Store");
            context.commit("REMOVE_One_Item", id);
        },

        changeTextData(context, txtnote, txtcity, txtaddress, txtphone) {
            context.commit("CHECK_And_Clear_Store");

            context.commit("SET_note", txtnote);
            context.commit("SET_city", txtcity);
            context.commit("SET_address", txtaddress);
            context.commit("SET_phone", txtphone);
        }
    },
    modules: {},
});