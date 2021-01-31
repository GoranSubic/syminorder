<template>
  <div class="container-fluid btncontainer">
<!--    <div class="row">-->
    <div class="mt-sm-3">
      <b-button-group size="sm">
        <b-button class="dec-btn" variant="warning" @click="decOneFromCart(id)">
          <i class="fas fa-minus"></i>
        </b-button>

        <b-button class="rem-btn" variant="danger" @click="remFromCart(id)">
          <i class="fa fa-trash"></i>
        </b-button>
        <b-button class="amm-btn" variant="outline-secondary" disabled >{{prodAmmount}}</b-button>

        <b-button class="inc-btn" variant="success" @click="addToCart(id, name, productcode, image, price, pricenumeric)">
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
  data: function () {
    return {
      dataId: this.id,
    }
  },
  beforeCreate() {
    this.$store.dispatch('initialiseStore');
  },
  props: {
    "id" : Number,
    "name": String,
    "productcode": String,
    "image": String,
    "price": String,
    "pricenumeric": Number,
    "plussign": String,
    "minussign": String,
  },
  computed: {
    prodAmmount() {
      var productAmm = this.$store.getters.products.find(prod => {
        return prod.id === this.dataId
      });
      return (typeof productAmm !== 'undefined' && productAmm.ammount > 0) ? productAmm.ammount : '';
    },
  },
  methods: {
    addToCart(id, name, productCode, image, price, priceNumeric) {
      var product = {
        id: id,
        name: name,
        productCode: productCode,
        image: image,
        price: price,
        priceNumeric: priceNumeric,
      };
      this.$store.dispatch("addItem", product);
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