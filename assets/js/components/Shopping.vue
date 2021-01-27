<template>
<div>
  <b-button v-b-modal.modal-tall :pressed="true" v-if="cartCount > 0" id="toggle-btn" @click="toggleModal" variant="secondary">
    <span class="info-wrapper">
      <i class="fa fa-shopping-bag"></i>
      <span class="cart-count">{{ cartCount }}</span>
    </span>
    <span> <span class="cart-sigma">  &Sigma;</span>{{ formatter.format(cartSumWithDelivery / 100) }}</span>
  </b-button>

  <b-modal ref="my-modal" hide-footer :title="titleText" id="modal-tall" class="fade">
    <div class="d-block text-center">
      <div class="text-right">
      <h6>
          <div>{{ titleText1 }}</div>
          <div>{{ titleText2 }}</div>
          <div>{{ titleText3 }}</div>
      </h6>
      </div>
      <hr />
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
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="cartNoteLabel">
                <i class="fas fa-tasks prefix"></i>
              </span>
            </div>
            <textarea type="text" id="cartNote" class="form-control form-control-sm" aria-describedby="cartNoteLabel"
                    rows="2" placeholder="Kratka napomena" @keyup="formChanged" @change="formChanged"></textarea>
          </div>

          <div class="input-group" v-if="cities.length">
            <div class="input-group-prepend">
              <span class="input-group-text" id="cartCityLabel">
                <i class="fas fa-table prefix"></i>
              </span>
            </div>
            <b-form-select required v-model="cityselected" :options="cities" id="cartCity" class="form-control"
                           aria-describedby="cartCityLabel" @change="formChanged">
            </b-form-select>

            <input id="cartDeliveryPrice" class="form-control" aria-describedby="cartCityLabel"
                   :value="formatter.format(cityDeliveryCalc / 100)" disabled>
          </div>

          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="cartUserNameLabel">
                <i class="fas fa-table prefix"></i>
              </span>
            </div>
            <input type="text" id="cartUserName" class="form-control" aria-describedby="cartUserNameLabel"
                   :data-user-id="userid" :value="datauname" aria-label="Username" hidden disabled>

            <input v-if="tableid === 0" required v-model="dataaddress" type="text" id="cartAddress" class="form-control" aria-describedby="cartUserNameLabel"
                   aria-label="Address" placeholder="Adresa za dostavu" @keyup="formChanged" @change="formChanged">
            <input v-if="tableid === 0" required v-model="dataphone" type="tel" id="cartPhone" class="form-control" aria-describedby="cartUserNameLabel"
                   aria-label="Phone" placeholder="Broj telefona" @keyup="formChanged" @change="formChanged">
            <input v-else-if="tableid !== 0" required type="text" id="cartTableName" class="form-control" aria-describedby="cartUserNameLabel"
                   :data-table-id="tableid" :value="tablename" aria-label="Tablename" disabled>
          </div>
        </div>

        <b-button v-if="cartCount > 0" id="btnFormSbm" @click="checkForm" variant="outline-success" block type="button">Prosledi</b-button>
      </form>

      <div class="footer">
        <p>Rok za isporuku hrane na kućnu adresu u Zrenjaninu je 60 minuta a za okolna sela je 120 minuta.</p>
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
      cities: [],
      cityselected: '',
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
      var txt = '';
      if(this.cartCount > 0) {
        if (this.cartCount > 0) txt = 'Prosledite porudžbinu ili o';
        else txt = 'O';
        txt += 'daberite još iz ponude';
      }
      return txt;
    },
    titleText1() {
      var txt = 'U korpi imate ' + this.cartCount + ' artikal(a)';
      if(this.cartCount > 0 && this.cartProductsSum > 0) {
        txt += ' u iznosu od ' + this.formatter.format(this.cartProductsSum / 100);
      }
      return txt;
    },
    titleText2() {
      var txt = '';
      // if (this.cityDeliveryCalc > 0) {
        txt += ' + Cena dostave: ' + this.formatter.format(this.cityDeliveryCalc / 100);
      // }
      return txt;
    },
    titleText3() {
      var txt = '';
      if (this.cartSumWithDelivery > 0) {
        txt += ' = Ukupno: ' + this.formatter.format(this.cartSumWithDelivery / 100);
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
    cartProductsSum() {
      var sum = 0;
      this.$store.getters.products.forEach(prod => {
        sum += (prod.priceNumeric * prod.ammount);
      });
      return (sum !== 'undefined' && sum > 0) ? (sum) : '';
    },
    cityDeliveryCalc() {
      var calc = 0;
      if (this.cityselected !== 'undefined' && this.cityselected.price !== 'undefined') {
        if (this.cityselected.deliveryFree === 'undefined'
            || (this.cityselected.deliveryFree === 0)
            || (this.cityselected.deliveryFree > this.cartProductsSum))
          calc = this.cityselected.price;
      }
      return calc;
    },
    cartSumWithDelivery() {
      var sum = 0;
      if (this.cartProductsSum !== 'undefined') sum += this.cartProductsSum;
      if (this.cityDeliveryCalc !== 'undefined') sum += this.cityDeliveryCalc;

      return sum;
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
      if (((this.cities.length === 0 || this.cityselected) && this.dataaddress && this.dataphone) || this.tableid) {
        // return true;
        this.handleSubmit();
      }

      this.validationErrors = [];

      if (!this.tableid) {
        if (!this.cityselected || this.cityselected === ''
            || !this.dataaddress || this.dataaddress === ''
            || !this.dataphone || this.dataphone === '')
        this.validationErrors.push('Mesto, adresa i telefon su obavezni!');
      }
    },

    formChanged() {
      this.datanote = document.getElementById("cartNote").value;
      if (this.cityselected !== '' && this.cityselected.address !== 'undefined'
          && this.cityselected.address !== null && this.cityselected.address !== '') {
        document.getElementById("cartAddress").innerText = this.cityselected.address;
        this.dataaddress = this.cityselected.address;
      } else {
        this.dataaddress = document.getElementById("cartAddress").value;
      }
      this.dataphone = document.getElementById("cartPhone").value;

      this.$store.dispatch("changeTextData", this.datanote, this.cityselected.name, this.dataaddress, this.dataphone);
    },

    itemsArray() {
      var itemsArray = [];
      this.$store.getters.products.forEach(prod => {
        var obj = {
          "product": "api/products/"+prod.id,
          "productCode": prod.productCode,
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
          "cityName": this.cityselected.name,
          "address": this.dataaddress,
          "phone": this.dataphone,
          "status": "cart",
          "cartAt": new Date(),
          "createdAt": this.$store.getters.storeCreated,
          "processAt": null,
          "transportAt": null,
          "deliveredAt": null,
          "updatedAt": new Date(),
          "deliveryPrice": this.cityselected.price,
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
            this.error = 'Sistemska greska!';
          }

        btn.disabled = false;
        btn.innerText = 'Ponovi slanje...';
      }).finally(() => {
        this.isLoading = false;
        this.$store.dispatch('clearStore');
      })
    },
    retrieveCityList() {
      var urlGet = 'api/city_deliveries?position[gte]=1';
      var paramsGet = {};

      axios.get(urlGet, {
        params: paramsGet
      })
          .then(response => {
            // JSON responses are automatically parsed.
            var resp = response.data["hydra:member"];
            resp.forEach(city => {
              var obj = {
                text: city.name,
                value: city
              };

              this.cities.push(obj);
            });
          })
          .catch(e => {
            this.errors.push(e)
          });
    },
  },
  created() {
    this.retrieveCityList();
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
#toggle-btn {
  position: relative;
  padding: 1px 12px 1px 12px !important;
}
#toggle-btn span.cart-count {
  position: absolute;
  left: 1.5em;
  top: 1em;
  /*to fit on small screen*/
  font-size: 0.8rem;
}
#toggle-btn .fa-shopping-bag {
  color: #e15f63;
  font-size: 2rem;
}
#toggle-btn span.cart-sigma {
  font-size: 0.8rem;
}

img {
  width: 100%;
}

form div.striped-row {
  background: #e0e0e0;
  margin-bottom: 3px;
}

form #cartCity, #cartDeliveryPrice, #cartAddress, #cartPhone {
  width: 45%;
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
  top: 0;
  left: 0;
  right: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>