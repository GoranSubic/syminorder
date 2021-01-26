<template>
  <div class="container-fluid btncontainer">
<!--    <div class="row">-->
    <b-button-group class="btnsgroup">
      <b-button variant="warning" @click="decOneFromCart(id)">
        <img class="sign minus" :src="minussign" alt="--" />
      </b-button>

      <b-button class="col col-6" variant="danger" @click="remFromCart(id)">
        X
      </b-button>
      <b-button class="col col-6" variant="outline-secondary" disabled >{{prodAmmount}}</b-button>

      <b-button variant="success" @click="addToCart(id, name, productcode, image, price, pricenumeric)">
        <img class="sign plus" :src="plussign" alt="++" />
      </b-button>
    </b-button-group>
<!--    </div>-->
  </div>
</template>

<script>
import store from '../store/index';

export default {
  store,
  name: "Product",
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
.btncontainer, .btnsgroup {
  padding-right: 0;
  padding-left: 0;
  width: 95%;
}

.btnsgroup button {
  width: 25%;
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