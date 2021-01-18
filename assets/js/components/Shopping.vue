<template>
<div>
<!--  <b-button v-b-modal.modal-tall>Launch overflowing modal</b-button>

  <b-modal id="modal-tall" title="Overflowing Content">
    <p class="my-4" v-for="i in 20" :key="i">
      Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
      in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
    </p>
  </b-modal>-->

  <b-button v-b-modal.modal-tall :pressed="true" v-if="cartCount > 0" id="toggle-btn" @click="toggleModal" variant="secondary">
    {{ cartCount + 'kom - ' + formatter.format(cartSum / 100) }}
  </b-button>

  <b-modal ref="my-modal" hide-footer :title="titleText" id="modal-tall" class="fade">
    <div class="d-block text-center">
      <h4>
        <span v-if="cartCount > 0">Prosledite porudžbinu ili o</span>
        <span v-else>O</span>daberite još iz ponude
      </h4>
<!--      <form v-on:submit.prevent="handleSubmit">-->
      <form>
        <div>
          <b v-if="error || validationErrors.length">Morate ispraviti grešku/e:</b>
          <div v-if="error" class="alert alert-danger">
            {{ error }}
          </div>
          <div v-if="validationErrors.length">
            <ul>
              <li v-for="err in validationErrors">{{ err }}</li>
            </ul>
          </div>
        </div>
        <div v-for="(item, index) in cart" :key="index" class="items-form">
          <div class="row striped-row">
            <div class="col-4"><img :src="item.image" :alt="item.name" /></div>
            <div class="col-6">
              <div class="ellipsis">
                <span>{{ item.name }}</span>
              </div>
              <div class="row">
                <div class="col-8 col-sm-5 ellipsis">
                  <span>{{ formatterNumber.format(item.priceNumeric/100) }}</span></div>
                <div class="col-4 col-sm-2 ellipsis" v-if="item.ammount !== undefined">
                  <span>{{ 'x' + item.ammount }}</span></div>
                <div class="col-12 col-sm-5 ellipsis" v-if="item.priceNumeric && item.ammount">
                  <span>{{ formatterNumber.format(item.priceNumeric * item.ammount/100) }}</span></div>
              </div>
            </div>
            <div class="col-2">
              <b-button style="width: 100%;" variant="danger" @click="removeItem(item.id)">
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

          <input v-if="tableid === 0" required v-model="dataaddress" type="text" id="cartAddress" class="form-control" aria-describedby="cartUserNameLabel"
                 aria-label="Address" placeholder="Adresa za dostavu" @keyup="formChanged" @change="formChanged">
          <input v-if="tableid === 0" required v-model="dataphone" type="text" id="cartPhone" class="form-control" aria-describedby="cartUserNameLabel"
                 aria-label="Phone" placeholder="Broj telefona" @keyup="formChanged" @change="formChanged">
          <input v-else-if="tableid !== 0" required type="text" id="cartTableName" class="form-control" aria-describedby="cartUserNameLabel"
                 :data-table-id="tableid" :value="tablename" aria-label="Tablename" disabled>
        </div>

        <b-button v-if="cartCount > 0" id="btnFormSbm" @click="checkForm" variant="outline-success" block type="button">Prosledi</b-button>
      </form>

      <div class="footer">
        <p>Isporuka se vrši u najkraćem mogućem roku.</p>
        <p>Nakon potvrđivanja status porudžbine možete pratiti na stranici Porudžbine</p>
      </div>
    </div>
  </b-modal>


  <b-modal ref="response-modal" hide-footer hide-header id="modal-response" class="fade">
    <div class="d-block text-center">
      <div class="margin-left-0 margin-right-0 col-xs-12 col-xs-offset-1 col-sm-12 col-md-12 col-lg-12 margin-bottom-15">
        <div class="modal-title">
          Uspešno ste izvršili porudžbinu
        </div>
      </div>

      <div class="margin-left-0 margin-right-0 col-xs-12 col-xs-offset-1 col-sm-12 col-md-12 col-lg-12 margin-bottom-15">
        <div id="register-modal-success-text" class="modal-text">
          Isporuka se vrši u najkraćem mogućem roku. <br><br>
          Status porudžbine možete pratiti na stranici Porudžbine.</div>
      </div>
      <div class="margin-left-0 margin-right-0 col-xs-12 col-xs-offset-1 col-sm-12 col-md-12 col-lg-12">
        <button class="btn btn-block btn-modal-submit" data-dismiss="modal" id="register-submit-success">OK</button>
      </div>
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
      formatter: Function,
      formatterNumber: Function,
      dataaddress: '',
      dataphone: '',
      datanote: '',
      error: '',
      validationErrors: [],
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
        txt += ' u iznosu od ' + this.formatter.format(this.cartSum / 100);
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
        sum += (prod.priceNumeric * prod.ammount);
      });
      return (sum !== 'undefined' && sum > 0) ? (sum) : '';
    },
  },
  methods: {
    showModalResponse() {
      this.$refs['response-modal'].show()
    },
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

    checkForm(e) {
      if ((this.dataaddress && this.dataphone) || this.tableid) {
        // return true;
        this.handleSubmit();
      }

      this.validationErrors = [];

      if (!this.tableid) {
        if (!this.dataaddress || this.dataaddress === '' || !this.dataphone || this.dataphone === '')
        this.validationErrors.push('Adresa i telefon su obavezni!');
      }
    },

    formChanged() {
      this.datanote = document.getElementById("cartNote").value;
      this.dataaddress = document.getElementById("cartAddress").value;
      this.dataphone = document.getElementById("cartPhone").value;

      this.$store.dispatch("changeTextData", this.datanote, this.dataaddress, this.dataphone);
    },

    itemsArray() {
      var itemsArray = [];
      this.$store.getters.products.forEach(prod => {
        var obj = {
          "product": "api/products/"+prod.id,
          "quantity": prod.ammount,
          "orderedItemPrice": prod.priceNumeric
        };
        itemsArray.push(obj);
      });
      return JSON.stringify(itemsArray);
    },
    handleSubmit() {
      var btn = document.getElementById('btnFormSbm');
      btn.disabled = true;
      btn.innerText = 'Slanje podataka...';

      this.isLoading = true;
      this.error = '';

      // utility for making ajax requests
      axios
        .post('/api/orders', {
          /*
          tableid: this.tableid,
          */
          "customer": "api/users/"+this.userid,
          "items": JSON.parse(this.itemsArray()),
          "noteCart": this.datanote,
          "noteAdmin": "",
          "address": this.dataaddress,
          "phone": this.dataphone,
          "status": "cart",
          "cartAt": new Date(),
          "createdAt": this.$store.getters.storeCreated,
          "processAt": null,
          "transportAt": null,
          "deliveredAt": null,
          "updatedAt": new Date(),
        })
        .then(response => {
          // console.log(response.data);
          // inform customer that can track orders status on page Porudžbine
          this.hideModal();
          this.showModalResponse();
        }).catch(error => {
          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'Unkown error!';
          }

        btn.disabled = false;
        btn.innerText = 'Ponovi slanje...';
      }).finally(() => {
        this.isLoading = false;
        this.$store.dispatch('clearStore');
      })
    },
  },
  created() {
    this.formatter= new Intl.NumberFormat('sr', {
      style: 'currency',
      currency: 'RSD',
      minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
      maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
    });
    this.formatterNumber= new Intl.NumberFormat('sr', {
      minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
      maximumFractionDigits: 2,
    });
  },
}
</script>

<style scoped>
img {
  width: 100%;
}

form div.striped-row {
  background: #e0e0e0;
  margin-bottom: 3px;
}

/*text in form*/
/*form .items-form {
  font-size: small;
}*/
.ellipsis {
  position: relative;
}
.ellipsis:before {
  content: '&nbsp;';
  visibility: hidden;
}
.ellipsis span {
  position: absolute;
  left: 0;
  right: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>