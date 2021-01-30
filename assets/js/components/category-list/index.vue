<template>
<div>
    <div id="sub-categories">
      <div class="categories container">

        <div class="row">
          <div class="col-12">
            <div class="mt-4">
              <loading v-show="categories.length === 0" />
            </div>
          </div>
          <category-card
              v-for="category in subcategoriesdata"
              :key="'/api/categories/' + category.id"
              :category="category"
              :getSubCategoriesParent="getSubCategories"
          >
          </category-card>
        </div>
      </div>
    </div>

    <div id="products">
      <div class="col-12">
        <div class="mt-4">
          <loading v-show="productsdata.length === 0" />
        </div>
      </div>
      <product-card v-for="product in productsdata"
                    :user_is_logged_in="user_is_logged_in"
                    :key="product['@id']"
                    :product="product"
                    class="col-6 col-sm-6 col-md-4 col-lg-3 products"
                    :id="'product-' + product.id"
      >
      </product-card>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Loading from "../Loading";
import CategoryCard from "./CategoryCard";
import ProductCard from "./ProductCard"

export default {
  name: "CategoryList",
  components: {
    Loading,
    CategoryCard,
    ProductCard,
  },
  props: {
    categories: {
      type: Array,
      required: true,
    },
    user_is_logged_in: Boolean,
    getSubCategoriesParent: Function,
    productsdata: Array,
    subcategoriesdata: Array,
    "plussign": String,
    "minussign": String,
  },
  methods: {
    getSubCategories(id) {
      this.getSubCategoriesParent(id);
    }
  },
}
</script>

<style scoped>

</style>