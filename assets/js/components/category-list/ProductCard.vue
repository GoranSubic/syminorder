<template>
  <div>
  <div class="prod-border">
      <div class="col-6 prod-image">
        <img v-if="product.picture && product.picture.imageFile"
            class="img-fluid prod-img"
            :src="product.picture.imageFile"
            :alt="product.name"
        />
        <span v-else>{{ product.name }}</span>
      </div>

      <div class="col-6 prod-info">
        <div class="prod-head">
          <div class="float-left col-12 prod-name">{{ product.name }}</div>
          <div class="col-12 prod-price">{{ formatter.format(product.price/100) }}</div>
        </div>
        <div class="prod-body">
          <div v-if="user_is_logged_in === true">
            <product-buttons
                :product="product"
            >
            </product-buttons>
          </div>
          <div v-else class="prod-desc" v-html="product.description"></div>
        </div>
      </div>
  </div>
  </div>
</template>

<script>
import ProductButtons from "../ProductButtons";

export default {
  name: "ProductCard",
  components: {
    ProductButtons
  },
  data() {
    return {
      formatter: Function,
      formatterNumber: Function,
    }
  },
  props: {
    product: {
      type: Object,
      required: true,
    },
    user_is_logged_in: {
      type: Boolean,
      default: false,
    },
  },
  created() {
    this.formatter = new Intl.NumberFormat('sr', {
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

</style>