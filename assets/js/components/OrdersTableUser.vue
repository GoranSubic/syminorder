<template>
<div class="ordersdata" v-if="ordersTotalNum > 0">
  <div class="d-block text-center">
    <h4>
      <span v-html="Translator.trans('vuejs.orderstableuser.orderstotalnum', { 'orderstotalnum': ordersTotalNum })"></span>
    </h4>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div class="ordertable table-responsive-md">
      <table class="table table-striped"  id="orders-list">
        <tr>
          <th>{{ Translator.trans('vuejs.orderstableuser.table.userinfo.index') }}</th>
          <th>{{ Translator.trans('vuejs.orderstableuser.table.userinfo.note') }}</th>
          <th>{{ Translator.trans('vuejs.orderstableuser.table.userinfo.address') }}</th>
          <th>{{ Translator.trans('vuejs.orderstableuser.table.userinfo.phone') }}</th>
          <th>{{ Translator.trans('vuejs.orderstableuser.table.userinfo.status') }}</th>
        </tr>
        <tbody>
        <template v-for="(ord, index) in orders">
          <tr :key="index">
            <td>
              {{ index + 1 }}
              <a v-on:click="showOrderItems(ord, index)"><b :id="'show-hide-' + index">Prikazi</b></a>
            </td>
            <td>{{ ord.noteCart }}</td>
            <td>{{ ord.address }}</td>
            <td>{{ ord.phone }}</td>
            <td :id="'cell-status-' + index" :class="'status-' + ord.status">
              {{ bcgColorStatus(ord.status, index) }}
            </td>
          </tr>

          <tr style="display: none" :id="'items-row-' + index">
            <td colspan="6">
              <table :class="'table-bordered table status-' + ord.status">
              <tr :class="'status-' + ord.status">
                <th>{{ Translator.trans('vuejs.orderstableuser.table.productsinfo.index') }}</th>
                <th>{{ Translator.trans('vuejs.orderstableuser.table.productsinfo.picture') }}</th>
                <th>{{ Translator.trans('vuejs.orderstableuser.table.productsinfo.name') }}</th>
                <th>{{ Translator.trans('vuejs.orderstableuser.table.productsinfo.quantity') }}</th>
                <th>{{ Translator.trans('vuejs.orderstableuser.table.productsinfo.price') }}</th>
                <th>{{ Translator.trans('vuejs.orderstableuser.table.productsinfo.total') }}</th>
              </tr>
              <tr v-for="(item, indexitem) in orderItems" :key="indexitem">
                <td>
                  {{ indexitem + 1 }}
                </td>
                <td>
                  <img class="img-thumbnail" :src="item.image" :alt="item.name">
                </td>
                <td>
                  <div class="row">
                    <div class="col-12">
                      <span>{{ item.name }}</span>
                    </div>
                    <div class="additions col-12" v-if="item.addselected !== ''">
                      <span>{{ Translator.trans('vuejs.orderstableuser.additions') }} <i class="addvalues">{{ item.addselected }}</i></span>
                    </div>
                  </div>
                </td>
                <td>{{ item.quantity }}</td>
                <td>{{ formatterNumber.format(item.price/100) }}</td>
                <td>{{ formatterNumber.format(item.itemSum/100) }}</td>
              </tr>
              <tr>
                <td></td>
                <td>{{ Translator.trans('vuejs.orderstableuser.delivery') }}</td>
                <td>{{ formatterNumber.format(ord.deliveryPrice/100) }}</td>
                <td>{{ Translator.trans('vuejs.orderstableuser.total') }}</td>
                <td>{{ formatterNumber.format(orderSum/100) }}</td>
                <td><b>{{ formatter.format((ord.deliveryPrice+orderSum)/100) }}</b></td>
              </tr>
            </table>
            </td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>

    <b-pagination v-if="ordersTotalNum > 10"
        @change="handlePageChange"
        v-model="currentPage"
        :total-rows="ordersTotalNum"
        :per-page="paginationItemsPerPage"
        pills
        :prev-text="Translator.trans('vuejs.orderstableuser.previous')"
        :next-text="Translator.trans('vuejs.orderstableuser.previous')"
    ></b-pagination>

  </div>
</div>
</template>

<script>
import axios from 'axios';
import {Translator} from "../main";

export default {
name: "OrdersTable",
  data() {
    return {
      Translator: Translator,
      formatter: Function,
      pagePagin: 1,
      currentPage: 1,
      paginationItemsPerPage: 10,
      patchValues: {},
      orders: [],
      ordersTotalNum: 0,
      orderItems: [],
      orderSum: 0,
      // errors: [],
      error: '',
    }
  },

  props: {
    searchby: String,
    userid: Number,
    datauname: String,
  },

  computed: {

  },

  methods: {
    bcgColorStatus: function (status, index) {
      // var colorStatus = document.getElementById('color-status-' + index);
      if (status !== 'undefined' && status !== null && status !== '') {
        if (status === 'cart') return Translator.trans('vuejs.orderstableuser.orderstatus.cart');
        if (status === 'processing') return Translator.trans('vuejs.orderstableuser.orderstatus.processing');
        if (status === 'driving') return Translator.trans('vuejs.orderstableuser.orderstatus.driving');
        if (status === 'delivered') return Translator.trans('vuejs.orderstableuser.orderstatus.delivered');
      }
    },

    handlePageChange(value) {
      this.pagePagin = value;

      this.retrieveUserOrders();
    },

    showOrderItems(ord, index) {
      var itemsArr = [];
      var orderSumM = 0;
      var itemsRow = document.getElementById('items-row-' + index);
      var showHide = document.getElementById('show-hide-' + index);

      if (ord.show !== 'undefined' && ord.show === true) {
        ord.show = false;
        itemsRow.style.display = 'none';
        showHide.innerHTML = Translator.trans('vuejs.orderstableuser.show');
      } else {
        ord.items.forEach(itm => {
          var itemSum = itm.quantity * itm.product.price;
          orderSumM += itemSum;
          var obj = {
            "image": '/images/products/' + itm.product.picture.imageName,
            "name": itm.product.name,
            "price": itm.product.price,
            "quantity": itm.quantity,
            "addselected": itm.itemAdditions,
            "itemSum": itemSum,
          };
          itemsArr.push(obj);
          this.orderSum = orderSumM;
        });
        // return itemsArr;
        this.orderItems = itemsArr;
        ord.show = true;
        itemsRow.style.display = 'table-row';
        showHide.innerHTML = Translator.trans('vuejs.orderstableuser.close');
      }
    },

    retrieveUserOrders() {
      var urlGet = 'api/users/' + this.userid + '/orders';
      var paramsGet = {};
      if (this.pagePagin) paramsGet["page"] = this.pagePagin;
      paramsGet["isDelivered"] = false;

      axios.get(urlGet, {
        params: paramsGet
      })
          .then(response => {
            // JSON responses are automatically parsed.
            this.orders = response.data["hydra:member"];
            this.ordersTotalNum = response.data["hydra:totalItems"]
          })
          .catch(e => {
            this.errors.push(e)
          });
    },
  },

  // Fetches orders when the component is created.
  created() {
    this.retrieveUserOrders();

    this.formatter = new Intl.NumberFormat('sr', {
      style: 'currency',
      currency: Translator.trans('vuejs.currency'),
      minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
      maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
    });
    this.formatterNumber= new Intl.NumberFormat('sr', {
      minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
      maximumFractionDigits: 2,
    });
  }
}
</script>

<style scoped>
table.status-cart {
  color: green;
}
table.status-processing {
  color: #B82227;
}
table.status-driving {
  color: darkorange;
}
table.status-delivered {
  color: lightskyblue;
}
tr.status-cart, td.status-cart {
  background-color: green !important;
  color: black !important;
}
tr.status-processing, td.status-processing {
  background-color: #B82227 !important;
  color: black !important;
}
tr.status-driving, td.status-driving {
  background-color: darkorange !important;
  color: black !important;
}
tr.status-delivered, td.status-delivered {
  background-color: lightskyblue !important;
  color: black !important;
}

.ordersdata {
  margin: 50px 0 50px 0;
  background-color: white;
  padding: 10px;
  border-radius: 10px;
}

.ordertable {
  width: 100%;
  max-height: 400px;
  overflow: auto;
}

.ordertable img {
  width: 70%;
}

table a {
  color: #db4c3e !important;
  line-height: 12px;
  text-decoration: underline !important;
}
table a:hover {
  cursor:pointer;
}
</style>