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
<!--      <form v-on:submit.prevent="handleSubmit">-->
      <form>
        <div v-if="error" class="alert alert-danger">
          {{ error }}
        </div>
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

        <div class="form-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="cartNoteLabel">
              <i class="fas fa-tasks prefix"></i>
            </span>
          </div>
          <textarea type="text" id="cartNote" class="form-control form-control-sm" aria-describedby="cartNoteLabel"
                    rows="2" placeholder="Kratka napomena" @keyup="formChanged" @change="formChanged"></textarea>
        </div>

        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="cartUserNameLabel">
              <i class="fas fa-table prefix"></i>
            </span>
          </div>
          <input type="text" id="cartUserName" class="form-control" aria-describedby="cartUserNameLabel"
                 :data-user-id="userid" :value="datauname" aria-label="Username" disabled>

          <input v-if="tableid === 0" type="text" id="cartAddress" class="form-control" aria-describedby="cartUserNameLabel"
                 aria-label="Address" placeholder="Adresa za dostavu" @keyup="formChanged" @change="formChanged">
          <input v-else-if="tableid !== 0" type="text" id="cartTableName" class="form-control" aria-describedby="cartUserNameLabel"
                 :data-table-id="tableid" :value="tablename" aria-label="Tablename" disabled>
        </div>

        <b-button v-if="cartCount > 0" @click="handleSubmit" variant="outline-success" block type="button">Prosledi</b-button>
      </form>
    </div>
  </b-modal>
</div>
</template>

<script>
import store from '../store/index';
import axios from 'axios';

export default {
  store,
  name: "Shopping",
  data() {
    return {
      dataaddress: '',
      datanote: '',
      error: '',
      isLoading: false,
    }
  },
  props: {
    minussign: String,
    datauname: String,
    userid: Number,
    tablename: String,
    tableid: Number,
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

    formChanged() {
      this.datanote = document.getElementById("cartNote").value;
      this.dataaddress = document.getElementById("cartAddress").value;

      this.$store.dispatch("changeTextData", this.datanote, this.dataaddress);
    },
    handleSubmit() {
      event.preventDefault();

      this.isLoading = true;
      this.error = '';

      // utility for making ajax requests
      axios
        .post('/api/orders', {
          /*
          tableid: this.tableid,
          products: this.$store.getters.products,
          */

          "customer": "api/users/"+this.userid,
          "items": [],
          "noteCart": this.datanote,
          "noteAdmin": "",
          "address": this.dataaddress,
          "status": "cart",
          "cartAt": new Date(),
          "createdAt": this.$store.getters.storeCreated,
          "processAt": this.$store.getters.storeCreated,
          "transportAt": this.$store.getters.storeCreated,
          "deliveredAt": this.$store.getters.storeCreated,
          "updatedAt": this.$store.getters.storeCreated
        })
        .then(response => {
          console.log(response.data);
        }).catch(error => {
          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'Unkown error!';
          }
      }).finally(() => {
        this.isLoading = false;
      })
    },
  },
}
</script>

<style scoped>
img {
  width: 100%;
}
</style>