<template>
<div class="ordersdata" v-if="ordersTotalNum > 0">
  <div class="d-block text-center">
    <h4>
      Lista porudžbina za dostavu - ukupno {{ ordersTotalNum }}
    </h4>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div class="ordertable table-responsive-md">
      <table class="table table-striped"  id="orders-list">
        <tr>
          <th>Index</th>
          <th>Napomena</th>
          <th>Adresa</th>
          <th>Telefon</th>
          <th>Status</th>
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
              <table :class="'table table-bordered status-' + ord.status">
              <tr>
                <th>Index</th>
                <th>Slika</th>
                <th>Naziv</th>
                <th>Kolicina</th>
                <th>Cena</th>
                <th>Ukupno</th>
              </tr>
              <tr v-for="(item, indexitem) in orderItems" :key="indexitem">
                <td>
                  {{ index + 1 }}
                </td>
                <td>
                  <img class="img-thumbnail" :src="item.image" :alt="item.name">
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.quantity }}</td>
                <td>{{ formatter.format(item.price/100) }}</td>
                <td>{{ formatter.format(item.itemSum/100) }}</td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Suma:</b></td>
                <td><b>{{ formatter.format(orderSum/100) }}</b></td>
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
        prev-text="Pret"
        next-text="Sled"
    ></b-pagination>

  </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
name: "OrdersTable",
  data() {
    return {
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
        if (status === 'cart') return 'Poslato';
        if (status === 'processing') return 'U pripremi';
        if (status === 'driving') return 'U transportu';
        if (status === 'delivered') return 'Preuzeto';
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
        showHide.innerHTML = 'Prikazi';
      } else {
        ord.items.forEach(itm => {
          var itemSum = itm.quantity * itm.product.price;
          orderSumM += itemSum;
          var obj = {
            "image": '/images/products/' + itm.product.picture.imageName,
            "name": itm.product.name,
            "price": itm.product.price,
            "quantity": itm.quantity,
            "itemSum": itemSum,
          };
          itemsArr.push(obj);
          this.orderSum = orderSumM;
        });
        // return itemsArr;
        this.orderItems = itemsArr;
        ord.show = true;
        itemsRow.style.display = 'table-row';
        showHide.innerHTML = 'Sakri';
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
      currency: 'RSD',
      minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
      maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
    });
  }
}
</script>

<style scoped>
.status-cart {
  background-color: green;
}
.status-processing {
  background-color: #B82227;
}
.status-driving {
  background-color: darkorange;
}
.status-delivered {
  background-color: lightskyblue;
}

.ordersdata {
  margin: 50px 0 50px 0;
}

.ordertable {
  width: 100%;
  height: 400px;
  overflow: auto;
}

.ordertable img {
  width: 30%;
}
</style>