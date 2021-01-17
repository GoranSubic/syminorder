<template>
  <div class="container btncontainer">
    <div class="row">
        <b-button variant="success" class="col col-12" @click="addToCart(id, name, image, price, pricenumeric)">
          <img class="sign plus" :src="plussign" alt="++" />
        </b-button>
    </div>
    <div class="row">
      <b-button-group class="col col-12 btnsgroup">
        <b-button class="col col-6" variant="danger" @click="remFromCart(id)">
          X
        </b-button>
        <b-button class="col col-6" variant="outline-secondary" disabled v-if="prodAmmount > 0">{{prodAmmount}}</b-button>
      </b-button-group>
    </div>
    <div class="row">
        <b-button variant="warning" class="col col-12" @click="decOneFromCart(id)">
          <img class="sign minus" :src="minussign" alt="--" />
        </b-button>
    </div>
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
      return (typeof productAmm !== 'undefined' && productAmm.ammount > 0) ? productAmm.ammount : 0;
    },
  },
  methods: {
    addToCart(id, name, image, price, priceNumeric) {
      var product = {
        id: id,
        name: name,
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