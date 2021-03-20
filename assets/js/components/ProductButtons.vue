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
              :placeholder="Translator.trans('vuejs.productbuttons.placeh_additions')" label="text" track-by="text"
              :preselect-first="false"
              deselectLabel=" - " selectLabel=" + " :selectedLabel="Translator.trans('vuejs.productbuttons.selected_label')"
          >
                    <template slot="selection" slot-scope="{ values, search, isOpen }">
                          <span class="multiselect__single"
                                v-if="values.length &amp;&amp; !isOpen">{{ Translator.trans('vuejs.productbuttons.selected_label_tmpl', { 'valueslength': values.length }) }}</span>
                    </template>
          </multiselect>
        </div>

        <b-button v-if="!product.showAdditions" class="dec-btn" @click="decOneFromCart(product.id)">
          <span v-html="Translator.trans('vuejs.productbuttons.button_decrease')"></span>
        </b-button>
        <b-button v-if="!product.showAdditions" class="rem-btn" @click="remFromCart(product.id)">
          <span v-html="Translator.trans('vuejs.productbuttons.button_remove')"></span>
        </b-button>
        <b-button v-if="!product.showAdditions" class="amm-btn" disabled >{{prodAmmount}}</b-button>

        <b-button class="inc-btn" @click="addToCart(product)">
          <span v-html="Translator.trans('vuejs.productbuttons.button_cart')"></span>
        </b-button>
      </b-button-group>
    </div>
<!--    </div>-->
  </div>
</template>

<script>
import store from '../store/index';
import {Translator} from "../main";

export default {
  store,
  name: "ProductButtons",
  beforeCreate() {
    this.$store.dispatch('initialiseStore');
  },
  data() {
    return {
      Translator: Translator,
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
.btncontainer {
  padding-top: 5px;
}

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
.inc-btn {
  background-color: #548fad;
}
.amm-btn {
  background-color: whitesmoke;
  color: black;
  font-size: x-small;
}
.dec-btn {
  background-color: black;
  /*color: #ffa500;*/
  font-size: x-small;
}
.rem-btn {
  background-color: black;
  /*color: #dc3545;*/
  font-size: x-small;
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