<template>
  <div class="container">
    <div class="row">
        <b-button :pressed="true" variant="success" class="col col-12" @click="addToCart(id, name, image, price)">
          <img class="sign plus" :src="plussign" alt="++" />
        </b-button>
    </div>
    <div class="row">
      <b-button-group>
        <b-button :pressed="true" variant="danger" @click="remFromCart(id)">
          X
        </b-button>
        <b-button variant="outline-secondary" disabled v-if="prodAmmount > 0">{{prodAmmount}}</b-button>
      </b-button-group>
    </div>
    <div class="row">
        <b-button :pressed="true" variant="warning" class="col col-12" @click="decOneFromCart(id)">
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
  props: {
    "id" : Number,
    "name": String,
    "image": String,
    "price": Number,
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
    addToCart(id, name, image, price) {
      var product = {
        id: id,
        name: name,
        image: image,
        price: price,
      };
      this.$store.dispatch("addItem", product);
    },
    remFromCart(id) {
      this.$store.dispatch("removeItem", id);
    },
    decOneFromCart(id) {
      this.$store.dispatch("decreaseItem", id);
    },
  }
}
</script>

<style scoped>
button {
  width: auto;
  border: 2px solid;
}

.row {
  height: 33% !important;
  margin: 0 !important;
}

img.sign {
  width: auto !important;
}
</style>