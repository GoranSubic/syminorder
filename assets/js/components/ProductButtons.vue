<template>
  <div class="container-fluid btncontainer">
    <!--    <div class="row">-->
    <div class="mt-sm-3 mt-buttons">
      <b-button-group size="sm">
<!--        <b-form-select class="form-control" aria-describedby=""
                       v-if="product.showAdditions && additions.length > 0"
                       v-model="addselected" :options="additions" multiple
        ></b-form-select>-->

        <div v-if="product.showAdditions && additions.length > 0" class="cont-multisel">
          <!--          <label class="typo__label">Simple select / dropdown</label>-->
          <multiselect
              v-model="addselected"
              :options="additions" :multiple="true"
              :close-on-select="false" :clear-on-select="false"
              :preserve-search="true"
              placeholder="Dodaci" label="text" track-by="text"
              :preselect-first="false"
              deselectLabel=" - " selectLabel=" + " selectedLabel="Dodato"
          >
                    <template slot="selection" slot-scope="{ values, search, isOpen }">
                          <span class="multiselect__single"
                                v-if="values.length &amp;&amp; !isOpen">Dodato {{ values.length }}</span>
                    </template>
          </multiselect>
        </div>

        <b-button v-if="!product.showAdditions" class="dec-btn" variant="warning" @click="decOneFromCart(product.id)">
          <i class="fas fa-minus"></i>
        </b-button>
        <b-button v-if="!product.showAdditions" class="rem-btn" variant="danger" @click="remFromCart(product.id)">
          <i class="fa fa-trash"></i>
        </b-button>
        <b-button v-if="!product.showAdditions" class="amm-btn" variant="outline-secondary" disabled >{{prodAmmount}}</b-button>

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
  data() {
    return {
      addselected: [],
    }
  },
  props: {
    product: {
      type: Object,
      required: true,
    },
    additions: {
      type: Array,
      required: false
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
      var addstr = '';
      var addcode = '';
      addcode = product.id;
      if (this.addselected.length) {
        this.addselected.forEach(addition => {
          if (this.isNotEmptyObject(addition) && typeof addition['text'] !== 'undefined' && addition['text'] !== '') {
            addstr += addition['text'] + ' ';
            addcode += addition['code'];
          }
        });
      }
      var prod = {
        id: product.id,
        name: product.name,
        productCode: product.code,
        picture: product.picture,
        price: product.price,
        addselected: addstr,
        addcode: addcode,
      };
      this.$store.dispatch("addItem", prod);
    },
    remFromCart(id) {
      this.$store.dispatch("removeItem", id);
    },
    decOneFromCart(id) {
      this.$store.dispatch("decreaseItem", id);
    },
    isNotEmptyObject(obj){
      return JSON.stringify(obj) !== '{}';
    }
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

.cont-multisel {
  width: 75% !important;
}
.cont-multisel .multiselect__single {
  font-size: 0.7em !important;
}
.cont-multisel .multiselect__input {
  font-size: 0.7em !important;
}
.cont-multisel .multiselect__option {
  font-size: 0.7em !important;
}
.cont-multisel .multiselect__placeholder {
  font-size: 0.7em !important;
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