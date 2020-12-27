import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        products: [],
        StoreCart: [],
        storeCreated: new Date(),
    },
    getters: {
        products: (state) => state.products,
        StoreCart: (state) => state.StoreCart,
        storeCreated: (state) => state.storeCreated,
    },
    mutations: {
        checkAndClearStore(state, init) {
            //check if store is older than 1 hour
            var now = new Date();
            var storeDate = new Date(state.storeCreated);
            var diff = parseInt(now - storeDate)/60000;
            if(diff > 60) {
                //reset localStorage and state
                localStorage.removeItem('store-olala');
                state.products = [];
                state.StoreCart = [];
                state.storeCreated = new Date();
                //reload page after add, remove item if store older than 1 hour
                if(typeof init === 'undefined' || init !== 'init') location.reload();
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
            var item = state.products.find(productItem => (productItem.id === product.id));
            if(typeof item !== 'undefined' && item !== null &&
                item.ammount !== 'undefined' && item.ammount > 0) {
                item.ammount += 1;
            } else {
                product.ammount = 1;
                state.products.push(product);
            }
            state.StoreCart.push(product.id);
        },

        /*REMOVE_Item(state, index) {
            state.StoreCart.splice(index, 1);
        },*/

        REMOVE_ItemById(localStorage, id) {
            localStorage.products = localStorage.products.filter(product => (product.id !== id));
            localStorage.StoreCart = localStorage.StoreCart.filter(storeId => (storeId !== id));
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
    },
    actions: {
        initialiseStore(context) {
            context.commit("Initialise_Store");
            context.commit("checkAndClearStore", "init");
        },

        addItem(context, product) {
            context.commit("checkAndClearStore");
            context.commit("ADD_Item", product);
        },

        removeItem(context, id) {
            context.commit("checkAndClearStore");
            context.commit("REMOVE_ItemById", id);
        },

        decreaseItem(context, id) {
            context.commit("checkAndClearStore");
            context.commit("REMOVE_One_Item", id);
        },
    },
    modules: {},
});