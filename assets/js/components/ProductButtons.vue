<template>
  <div class="container-fluid btncontainer">
<!--    <div class="row">-->
    <div class="mt-sm-3">
      <b-button-group size="sm">
        <b-button class="dec-btn" variant="warning" @click="decOneFromCart(product.id)">
          <i class="fas fa-minus"></i>
        </b-button>

        <b-button class="rem-btn" variant="danger" @click="remFromCart(product.id)">
          <i class="fa fa-trash"></i>
        </b-button>
        <b-button class="amm-btn" variant="outline-secondary" disabled >{{prodAmmount}}</b-button>

        <b-button class="inc-btn" variant="success" @click="addToCart(product)">
          <i class="fas fa-cart-plus"></i>
        </b-button>
      </b-button-group>
    </div>
<!--    </div>-->
  </div>
</template>

<script>
import store from '../store/index';

export default {
  store,
  name: "ProductButtons",
  beforeCreate() {
    this.$store.dispatch('initialiseStore');
  },
  props: {
    product: {
      type: Object,
      required: true,
    },
  },
  computed: {
    prodAmmount() {
      var productAmm = this.$store.getters.products.find(prod => {
        return prod.id === this.product.id
      });
      return (typeof productAmm !== 'undefined' && productAmm.ammount > 0) ? productAmm.ammount : '';
    },
  },
  methods: {
    addToCart(product) {
      var prod = {
        id: product.id,
        name: product.name,
        productCode: product.code,
        picture: product.picture,
        price: product.price,
      };
      this.$store.dispatch("addItem", prod);
    },
    remFromCart(id) {
      this.$store.dispatch("removeItem", id);
    },
    decOneFromCart(id) {
      this.$store.dispatch("decreaseItem", id);
    },
  },
}
</script>

<style scoped>
.btn-group {
  /*padding-right: 0;*/
  /*padding-left: 0;*/
  width: 100%;

  justify-content: center;
}

button.btn {
  width: 25%;
}
button.btn svg {
  padding: 1px;
}

.inc-btn, .dec-btn, .rem-btn, .amm-btn {
  display: flex;
  align-items: center;
  justify-content: center;
}

.row {
  height: 33% !important;
  margin: 0 !important;
}

img.sign {
  height: 10px !important;
  width: auto !important;
}
</style>
