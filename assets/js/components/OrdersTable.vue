<template>
<div class="ordersdata" v-if="ordersTotalNum > 0">
  <div class="d-block text-center">
    <h4>
      <span v-html="Translator.trans('vuejs.orderstable.orderstotalnum', { 'orderstotalnum': ordersTotalNum })"></span>
    </h4>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <b-pagination
        @change="handlePageChange"
        v-model="currentPage"
        :total-rows="ordersTotalNum"
        :per-page="paginationItemsPerPage"
        pills
        :first-text="Translator.trans('vuejs.orderstable.first')"
        :prev-text="Translator.trans('vuejs.orderstable.previous')"
        :next-text="Translator.trans('vuejs.orderstable.next')"
        :last-text="Translator.trans('vuejs.orderstable.last')"
    ></b-pagination>

    <div class="ordertable table-responsive-md">
      <table class="table table-striped"  id="orders-list">
        <tr>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.index') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.picture') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.customer') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.note') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.city') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.address') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.phone') }}</th>
          <th>{{ Translator.trans('vuejs.orderstable.table.userinfo.submitto') }}</th>
        </tr>
        <tbody>
        <template v-for="(ord, index) in orders">
          <tr :key="index">
            <td>
              {{ index + 1 }}
              <a v-on:click="showOrderItems(ord, index)"><b :id="'show-hide-' + index">Prikazi</b></a>
            </td>
            <td>
              <img class="img-thumbnail" :src="ord.customer.pictureUrl" :alt="ord.customer.username">
            </td>
            <td>{{ ord.customer.username }}</td>
            <td>{{ ord.noteCart }}</td>
            <td>{{ ord.cityName }}</td>
            <td>{{ ord.address }}</td>
            <td>{{ ord.phone }}</td>
            <td>
              <b-button @click="forwardStatusTo(ord, index)" value="Prosledi" type="button" :class="'status-' + ord.status" :id="'btn-sand-' + index">
                {{ btnColorStatus(ord.status, index) }}
              </b-button>
            </td>
          </tr>

          <tr style="display: none" :id="'items-row-' + index">
            <td colspan="6">
              <table :class="'table table-bordered status-' + ord.status">
              <tr :class="'status-' + ord.status">
                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.index') }}</th>
<!--                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.picture') }}</th>-->
                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.name') }}</th>
                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.code') }}</th>
                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.quantity') }}</th>
                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.price') }}</th>
                <th>{{ Translator.trans('vuejs.orderstable.table.productsinfo.total') }}</th>
              </tr>
              <tr v-for="(item, indexitem) in orderItems" :key="indexitem">
                <td>
                  {{ indexitem + 1 }}
                </td>
<!--                <td>
                  <img class="img-thumbnail" :src="item.image" :alt="item.name">
                </td>-->
                <td>
                  <div class="row">
                    <div class="col-12">
                      <span>{{ item.name }}</span>
                    </div>
                    <div class="additions col-12" v-if="item.addselected !== ''">
                      <span>{{ Translator.trans('vuejs.orderstable.additions') }} <i class="addvalues">{{ item.addselected }}</i></span>
                    </div>
                  </div>
                </td>
                <td>{{ item.productCode }}</td>
                <td>{{ item.quantity }}</td>
                <td>{{ formatterNumber.format(item.price/100) }}</td>
                <td>{{ formatterNumber.format(item.itemSum/100) }}</td>
              </tr>
              <tr>
                <td></td>
<!--                <td></td>-->
                <td>{{ Translator.trans('vuejs.orderstable.delivery') }}</td>
                <td>{{ formatterNumber.format(ord.deliveryPrice/100) }}</td>
                <td>{{ Translator.trans('vuejs.orderstable.total') }}</td>
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
    btnColorStatus: function (status, index) {
      // var colorStatus = document.getElementById('btn-sand-' + index);
      if (status !== 'undefined' && status !== null && status !== '') {
        if (status === 'cart') return 'Nova';
        if (status === 'processing') return 'Priprema';
        if (status === 'driving') return 'Transport';
        if (status === 'delivered') return 'Preuzeto';
      }
    },

    handlePageChange(value) {
      this.pagePagin = value;

      this.retrieveAdminOrders();
    },

    forwardStatusTo(ord, index) {
      this.patchValues = {};
      switch (ord.status) {
        case 'cart':
          this.patchValues['processAt'] = new Date();
          this.patchValues['updatedAt'] = new Date();
          ord.processAt = new Date();
          ord.updatedAt = new Date();
          this.patchStatus(ord.id, 'processing', index)
          break;
        case 'processing':
          this.patchValues['transportAt'] = new Date();
          this.patchValues['updatedAt'] = new Date();
          ord.transportAt = new Date();
          ord.updatedAt = new Date();
          this.patchStatus(ord.id, 'driving', index)
          break;
        case 'driving':
          this.patchValues['deliveredAt'] = new Date();
          this.patchValues['updatedAt'] = new Date();
          this.patchValues['isDelivered'] = true;
          ord.deliveredAt = new Date();
          ord.updatedAt = new Date();
          ord.isDelivered = true;
          this.patchStatus(ord.id, 'delivered', index)
          break;
        default:
          console.log('Order should be delivered! Status is: ', ord.status);
          this.error = 'Porudžbina je već isporučena!';
          break;
      }
    },

    patchStatus(orderId, status, index) {
      this.patchValues["status"] = status;
      var url = 'api/orders/' + orderId;
      require('axios').create({
        headers: {
          patch: {
            'Content-Type': 'application/ld+json'
          }
        },
      }).request({
        url: url,
        method: 'patch',
        data: JSON.stringify(
            this.patchValues
        )
      }).then(response => {
        var btnSandStatus = document.getElementById('btn-sand-' + index);
        btnSandStatus.innerHTML = status;
        // todo remove this row or reload
        location.reload();
      }).catch(error => {
          // this.errors.push(error);

          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'Sistemska greska!';
          }
        });
    },

    showOrderItems(ord, index) {
      var itemsArr = [];
      var orderSumM = 0;
      var itemsRow = document.getElementById('items-row-' + index);
      var showHide = document.getElementById('show-hide-' + index);

      if (ord.show !== 'undefined' && ord.show === true) {
        ord.show = false;
        itemsRow.style.display = 'none';
        showHide.innerHTML = 'Prikazi';
      } else {
        ord.items.forEach(itm => {
          var itemSum = itm.quantity * itm.product.price;
          orderSumM += itemSum;
          var obj = {
            "image": '/images/products/' + itm.product.picture.imageName,
            "name": itm.product.name,
            "productCode": itm.product.code,
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
        showHide.innerHTML = 'Skupi';
      }
    },

    retrieveAdminOrders() {
      var paramsGet = {};
      if (this.pagePagin) paramsGet["page"] = this.pagePagin;
      if (this.searchby !== 'undefined' && this.searchby !== '') paramsGet["status"] = this.searchby;
      paramsGet["isDelivered"] = false;

      axios.get(`api/orders`, {
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
    this.retrieveAdminOrders();

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
tr.status-cart, .btn.status-cart {
  background-color: green !important;
  color: black !important;
}
tr.status-processing, .btn.status-processing {
  background-color: #B82227 !important;
  color: black !important;
}
tr.status-driving, .btn.status-driving {
  background-color: darkorange !important;
  color: black !important;
}
tr.status-delivered, .btn.status-delivered {
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
  color: #548fad !important;
  line-height: 12px;
  text-decoration: underline !important;
}
table a:hover {
  cursor:pointer;
}
</style>