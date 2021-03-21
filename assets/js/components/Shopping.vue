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
          <b v-if="error || validationErrors.length">{{ Translator.trans('vuejs.shopping.error') }}:</b>
          <div v-if="error" class="alert alert-danger">
            {{ error }}
          </div>
          <div v-if="validationErrors.length" class="alert alert-danger">
            <ul>
              <li v-for="err in validationErrors">{{ err }}</li>
            </ul>
          </div>
        </div>
        <div v-for="(item, index) in cart" :key="index" class="items-form">
          <div class="row striped-row">
            <div class="col-4">
              <img
                  v-if="item.picture && item.picture.imageName"
                  class="img-fluid prod-img"
                  :src="'/images/products/' + item.picture.imageName"
                  :alt="item.name"
              />
              <span v-else>{{ item.name }}</span>
            </div>
            <div class="col-6">
              <div class="row">
                <div class="col-12 ellipsis">
                  <span>{{ item.name }}</span>
                </div>
                <div class="additions col-12 ellipsis" v-if="item.addselected !== ''">
                  <span>{{ Translator.trans('vuejs.shopping.additions') }}: <i class="addvalues">{{ item.addselected }}</i></span>
                </div>
              </div>
              <div class="row">
                <div class="col-8 col-sm-5 ellipsis">
                  <span>{{ formatterNumber.format(item.price/100) }}</span></div>
                <div class="col-4 col-sm-2 ellipsis" v-if="item.ammount !== undefined">
                  <span>{{ 'x' + item.ammount }}</span></div>
                <div class="col-12 col-sm-5 ellipsis" v-if="item.price && item.ammount">
                  <span>{{ formatterNumber.format(item.price * item.ammount/100) }}</span></div>
              </div>
            </div>
            <div class="col-2">
              <b-button class="rem-btn" style="width: 100%;" variant="danger" @click="removeItem(item.id, item.addcode)">
                <span v-html="Translator.trans('vuejs.shopping.button_remove')"></span>
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
                    rows="2" :placeholder="Translator.trans('vuejs.shopping.placeh_short_note')" @keyup="formChanged" @change="formChanged"></textarea>
          </div>

          <div class="input-group" v-if="this.hasdefinedcities === 'define_cities' && cities.length">
            <div class="input-group-prepend">
              <span class="input-group-text" id="cartCityLabel">
                <i class="fa fa-map-marker-alt"></i>
              </span>
            </div>

            <multiselect
                v-model="cityselected"
                :options="cities" id="cartCity" class="form-control"
                aria-describedby="cartCityLabel" @select="formChanged"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="false"
                :placeholder="Translator.trans('vuejs.shopping.placeh_place_city')" label="name" track-by="name"
                :preselect-first="true"
                deselectLabel=" - " selectLabel=" + " :selectedLabel="Translator.trans('vuejs.shopping.selected')"
            >
              <template slot="singleLabel" slot-scope="{ option }" class="city-select" style="height: 30px; width: 35%;">
                          <span v-if="option.name">{{ option.name }}</span>
              </template>
            </multiselect>

            <div class="input-group-prepend">
              <span class="input-group-text" id="cartCityPriceLabel">
                <i class="fas fa-money-bill-wave-alt prefix"></i>
              </span>
            </div>
            <input id="cartDeliveryPrice" class="form-control" aria-describedby="cartCityPriceLabel"
                   :value="formatter.format(cityDeliveryCalc / 100)" disabled>
          </div>
          <div class="input-group" v-if="this.hasdefinedcities === 'not_define_cities'">
            <div class="input-group-prepend">
              <span class="input-group-text" id="cartSingleCityLabel">
                <i class="fa fa-map-marker-alt"></i>
              </span>
            </div>
            <input v-if="tableid === 0" required v-model="datacity" type="text" id="cartSingleCity" class="form-control" aria-describedby="cartSingleCityLabel"
                   aria-label="City" :placeholder="Translator.trans('vuejs.shopping.placeh_place_city')" @keyup="formChanged" @change="formChanged">
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
                   aria-label="Address" :placeholder="Translator.trans('vuejs.shopping.placeh_delivery_address')" @keyup="formChanged" @change="formChanged">

            <div class="input-group-prepend" v-if="tableid === 0">
              <span class="input-group-text" id="cartPhoneLabel">
                <i class="fas fa-phone-alt prefix"></i>
              </span>
            </div>
            <input v-if="tableid === 0" required v-model="dataphone" type="tel" id="cartPhone" class="form-control" aria-describedby="cartPhoneLabel"
                   aria-label="Phone" :placeholder="Translator.trans('vuejs.shopping.placeh_phone_num')" @keyup="formChanged" @change="formChanged">
            <input v-else-if="tableid !== 0" required type="text" id="cartTableName" class="form-control" aria-describedby="cartUserNameLabel"
                   :data-table-id="tableid" :value="tablename" aria-label="Tablename" disabled>
          </div>
        </div>

        <b-button v-if="cartCount > 0" id="btnFormSbm" @click="checkForm" variant="outline-success" block type="button">{{ Translator.trans('vuejs.shopping.button_submit') }}</b-button>
      </form>

      <div class="footer">
        <span v-html="Translator.trans('vuejs.shopping.footer_info')"></span>
      </div>
    </div>
  </b-modal>

  <b-modal ref="response-modal" hide-footer hide-header id="modal-response" class="fade">
    <div class="d-block text-center">
      <div class="margin-left-0 margin-right-0 col-xs-12 col-xs-offset-1 col-sm-12 col-md-12 col-lg-12 margin-bottom-15">
        <div class="modal-title">
          <span v-html="Translator.trans('vuejs.modal.order_submited.title')"></span>
        </div>
      </div>

      <div class="margin-left-0 margin-right-0 col-xs-12 col-xs-offset-1 col-sm-12 col-md-12 col-lg-12 margin-bottom-15">
        <div id="register-modal-success-text" class="modal-text">
          <span v-html="Translator.trans('vuejs.modal.order_submited.message')"></span>
        </div>
      </div>
      <div class="margin-left-0 margin-right-0 col-xs-12 col-xs-offset-1 col-sm-12 col-md-12 col-lg-12">
        <button class="btn btn-block btn-modal-submit" data-dismiss="modal" id="register-submit-success">
          <span v-html="Translator.trans('vuejs.modal.order_submited.button_ok')"></span>
        </button>
      </div>
    </div>
  </b-modal>
</div>
</template>

<script>
import store from '../store/index';
import axios from 'axios';
import {Translator} from "../main";

export default {
  store,
  name: "Shopping",
  data() {
    return {
      Translator: Translator,
      formatter: Function,
      formatterNumber: Function,
      datacity: '',
      dataaddress: '',
      dataphone: '',
      datanote: '',
      error: '',
      validationErrors: [],
      isLoading: false,
      cities: [],
      cityselected: {
        name: Translator.trans('vuejs.shopping.first_place_city'),
        value: null,
        price: 0,
        address: null,
        deliveryFree: 0,
      },
    }
  },
  props: {
    datauname: String,
    userid: Number,
    tablename: String,
    tableid: Number,
    hasdefinedcities: String,
  },
  computed: {
    titleText() {
      var txt = '';

      if (this.cartCount > 0) txt = Translator.trans('vuejs.shopping.title_part1');
      else txt = Translator.trans('vuejs.shopping.title_part2');

      txt += Translator.trans('vuejs.shopping.title_part3');
      return txt;
    },
    titleText1() {
      var txt = Translator.trans('vuejs.shopping.title_article_num1', {'cartcount': this.cartCount});
      if(this.cartCount > 0 && this.cartProductsSum > 0) {
        txt += Translator.trans('vuejs.shopping.title_article_num2', {'cartproductssum': this.formatter.format(this.cartProductsSum / 100)});
      }
      return txt;
    },
    titleText2() {
      var txt = '';
      // if (this.cityDeliveryCalc > 0) {
        txt += Translator.trans('vuejs.shopping.title_delivery_price', {'citydeliverycalc': this.formatter.format(this.cityDeliveryCalc / 100)});
      // }
      return txt;
    },
    titleText3() {
      var txt = '';
      if (this.cartSumWithDelivery > 0) {
        txt += Translator.trans('vuejs.shopping.title_sum', {'cartsumwithdelivery': this.formatter.format(this.cartSumWithDelivery / 100)});
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
        sum += (prod.price * prod.ammount);
      });
      return (sum !== 'undefined' && sum > 0) ? (sum) : '';
    },
    cityDeliveryCalc() {
      var calc = 0;
      if (this.cityselected !== null && this.cityselected !== 'undefined' && this.cityselected.price !== 'undefined') {
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
    removeItem(id, addcode) {
      if (typeof addcode !== 'undefined' && addcode !== '') {
        this.$store.dispatch("removeItemByAddcode", addcode);
      } else {
        this.$store.dispatch("removeItem", id);
      }
    },

    checkForm(e) {
      var citiesCheck = (this.hasdefinedcities === 'define_cities' && ((this.cities.length === 0 || this.cityselected) || (this.cityselected)))
                        || (this.hasdefinedcities === 'not_define_cities' && this.datacity);

      if ((citiesCheck && this.dataaddress && this.dataphone) || this.tableid) {
        // return true;
        this.handleSubmit();
      }

      this.validationErrors = [];

      if (!this.tableid) {
        if (!citiesCheck  //!this.cityselected || this.cityselected === ''
            || !this.dataaddress || this.dataaddress === ''
            || !this.dataphone || this.dataphone === '') {
          var error_msg = Translator.trans('vuejs.shopping.error_msg');
          this.validationErrors.push(error_msg);
        }
      }
    },

    formChanged() {
      this.datanote = document.getElementById("cartNote").value;
      if (this.hasdefinedcities === 'define_cities' && this.cityselected !== null && this.cityselected !== '' && this.cityselected.address !== 'undefined'
          && this.cityselected.address !== null && this.cityselected.address !== '') {
        document.getElementById("cartAddress").innerText = this.cityselected.address;
        this.dataaddress = this.cityselected.address;
      } else {
        this.datacity = document.getElementById("cartSingleCity").value;
        this.dataaddress = document.getElementById("cartAddress").value;
      }
      this.dataphone = document.getElementById("cartPhone").value;

      if (this.hasdefinedcities === 'define_cities') {
        this.$store.dispatch("changeTextData", this.datanote, this.cityselected.name, this.dataaddress, this.dataphone);
      } else {
        this.$store.dispatch("changeTextData", this.datanote, this.datacity, this.dataaddress, this.dataphone);
      }
    },

    itemsArray() {
      var itemsArray = [];
      this.$store.getters.products.forEach(prod => {
        var obj = {
          "product": "/api/products/"+prod.id,
          "productCode": prod.productCode,
          "quantity": prod.ammount,
          "itemAdditions": prod.addselected,
          "orderedItemPrice": prod.price
        };
        itemsArray.push(obj);
      });
      return JSON.stringify(itemsArray);
    },
    handleSubmit() {
      var btn = document.getElementById('btnFormSbm');
      btn.disabled = true;
      btn.innerText = Translator.trans('vuejs.shopping.button_submit_txt');

      this.isLoading = true;
      this.error = '';

      // utility for making ajax requests
      axios
        .post('/api/orders', {
          /*
          tableid: this.tableid,
          */
          "customer": "/api/users/"+this.userid,
          "items": JSON.parse(this.itemsArray()),
          "noteCart": this.datanote,
          "noteAdmin": "",
          "cityName": (this.hasdefinedcities === "define_cities" ? this.cityselected.name : this.datacity),
          "address": this.dataaddress,
          "phone": this.dataphone,
          "status": "cart",
          "cartAt": new Date(),
          "createdAt": this.$store.getters.storeCreated,
          "processAt": null,
          "transportAt": null,
          "deliveredAt": null,
          "updatedAt": new Date(),
          "deliveryPrice": this.hasdefinedcities === "define_cities" ? this.cityselected.price : 0,
        })
        .then(response => {
          // console.log(response.data);
          // inform customer that can track orders status on page Porudžbine
          this.hideModal();
          this.showModalResponse();
        }).catch(error => {
          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else if (error.response.data && error.response.data["hydra:description"]) {
            this.error = error.response.data["hydra:description"];
          } else {
            this.error = Translator.trans('vuejs.shopping.error_submit_post');
          }

        btn.disabled = false;
        btn.innerText = Translator.trans('vuejs.shopping.send_again');
      }).finally(() => {
        this.isLoading = false;
        this.$store.dispatch('clearStore');
      })
    },
    retrieveCityList() {
      var urlGet = '/api/city_deliveries?position[gte]=1';
      var paramsGet = {};

      axios.get(urlGet, {
        params: paramsGet
      })
          .then(response => {
            // JSON responses are automatically parsed.
            var resp = response.data["hydra:member"];
            /*if (resp.length > 0) {
              this.cities.push(
                  { name: 'Dostava / Lično',
                    value: null,
                    price: 0,
                    address: null,
                    deliveryFree: 0,
                  },
              )
            }*/
            resp.forEach(city => {
              var obj = {
                name: city.name,
                value: city,
                price: city.price,
                address: city.address,
                deliveryFree: city.deliveryFree,
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
    if (this.hasdefinedcities === 'define_cities') {
      this.retrieveCityList();
    }
    this.formatter= new Intl.NumberFormat('sr', {
      style: 'currency',
      currency: Translator.trans('vuejs.currency'),
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
  color: #548fad;
  font-size: 2rem;
}
#toggle-btn span.cart-sigma {
  font-size: 0.8rem;
}

.rem-btn {
  display: flex;
  align-items: center;
  justify-content: center;
}

img {
  width: 100%;
}

form div.striped-row {
  background: #e0e0e0;
  margin-bottom: 3px;
}

.multiselect.form-control {
  width: 35%;
  padding: 0;
  height: 30px !important;
}
form #cartCity, #cartDeliveryPrice, #cartAddress, #cartPhone {
  width: 40%;
}
.input-group-prepend {
  width: 10%;
  padding: 1px;
  display: flex;
  align-items: center;
  justify-content: center;
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
.additions {
  font-size: 0.7em;
}
.additions .addvalues {
  color: #548fad;
}

form input[required=required]:before,
form select[required=required]:before
{
  color: #548fad;
  content: " *";
}
</style>