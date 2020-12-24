<template>
<div>
  <b-button :pressed="true" v-if="cartCount > 0" id="toggle-btn" @click="toggleModal" variant="secondary">
    {{ cartCount + 'kom - ' + cartSum + 'rsd' }}
  </b-button>

  <b-modal ref="my-modal" hide-footer :title="titleText" id="modal-cart">
    <div class="d-block text-center">
      <h4>
        <span v-if="cartCount > 0">Prosledite porudžbinu ili o</span>
        <span v-else>O</span>daberite još iz ponude
      </h4>

      <div v-for="(item, index) in cart" :key="index">
        <div class="row">
          <div class="col-2">{{ item.name }}</div>
          <div class="col-2"><img :src="item.image" :alt="item.name" /></div>

          <div class="col-2">{{ item.price }}</div>
          <div class="col-2" v-if="item.ammount !== undefined">{{ ' x ' + item.ammount }}</div>
          <div class="col-2" v-if="item.price && item.ammount">
            {{ (item.price * item.ammount) + 'Din' }}
          </div>
          <div class="col-2">
            <b-button style="width: 100%;" variant="outline-danger" @click="removeItem(item.id)">
              X
            </b-button>
          </div>
        </div>
      </div>
    </div>
    <b-button v-if="cartCount > 0" variant="outline-success" block type="submit">Submit</b-button>
  </b-modal>
</div>
</template>

<script>
import store from '../store/index';

export default {
  store,
  name: "Shopping",

  props: {
    "minussign": String,
  },
  computed: {
    titleText() {
      var txt = 'U korpi imate ' + this.cartCount + ' artikal(a)';
      if(this.cartCount > 0) {
        txt += ' u iznosu od ' + this.cartSum + 'rsd';
      }
      return txt;
    },
    StoreCart() {
      return this.$store.getters.StoreCart;
    },
    cart() {
      return this.$store.getters.products;
    },
    cartCount() {
      return this.StoreCart.length;
    },
    cartSum() {
      var sum = 0;
      this.$store.getters.products.forEach(prod => {
        sum += (prod.price * prod.ammount);
      });
      return (sum !== 'undefined' && sum > 0) ? (sum) : '';
    }
  },
  methods: {
    showModal() {
      this.$refs['my-modal'].show()
    },
    hideModal() {
      this.$refs['my-modal'].hide()
    },
    toggleModal() {
      // We pass the ID of the button that we want to return focus to
      // when the modal has hidden
      this.$refs['my-modal'].toggle('#toggle-btn')
    },

    removeItem(id) {
      this.$store.dispatch("removeItem", id);
    },
  }
}
</script>

<style scoped>
img {
  width: 100%;
}
</style>