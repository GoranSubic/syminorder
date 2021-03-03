<template>
<div>
    <div id="sub-categories">
      <div class="categories container">

        <div class="row">
<!--          <div class="col-12 wait-for">
            <div class="mt-4">
              <loading v-show="categories.length === 0" />
            </div>
          </div>-->
          <category-card
              v-if="category.enabled === true"
              v-for="category in subcategoriesdata"
              :key="'/api/categories/' + category.id"
              :category="category"
              :getSubCategoriesParent="getSubCategories"
          >
          </category-card>
        </div>
      </div>
    </div>

  <div v-if="searchtermfound !== 'undefined' && searchtermfound !== ''" class="row category-parent">
    <div class="col-12 category-info">
      <h4 class="col-12 category-name text-left">Pretraga po: "{{ this.searchtermfound }}"</h4>
      <!--      <div class="col-12 category-desc text-left" v-html="searchtermfound"></div>-->
    </div>
  </div>
  <div v-else class="row category-parent">
    <div class="col-2 parent-btn text-right">
      <b-button id="parentcat-btn" variant="secondary"
          :pressed="true"
          v-if="parentcatdata !== null"
          @click="getSubCategories(parentcatdata['id'])"
      >
        <span class="back-icon">
          <i class="fas fa-level-up-alt"></i>
        </span>
        <p class="back-name">{{ parentcatdata['name'] }}</p>
      </b-button>
    </div>
    <div class="col-10 category-info">
      <h4 class="col-12 category-name text-left" v-html="categoriesdata['name']"></h4>
      <div class="col-12 category-desc text-left" v-html="categoriesdata['description']"></div>
    </div>
  </div>

    <div id="products">
      <div class="col-12 wait-for">
        <div class="mt-4">
          <loading v-show="productsdata.length === 0" />
        </div>
      </div>
      <product-card
              v-if="product.enabled === true"
              v-for="product in productsdata"
              :user_is_logged_in="user_is_logged_in"
              :key="product['@id']"
              :product="product"
              class="col-12 col-sm-6 col-md-4 col-lg-4 products"
              :id="'product-' + product.id"
              :additions="additions"
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
    parentcatdata: Object,
    categoriesdata: Object,
    productsdata: Array,
    subcategoriesdata: Array,
    additions: {
      type: Array,
      required: false
    },
    searchtermfound: String
  },
  methods: {
    getSubCategories(id) {
      this.getSubCategoriesParent(id);
    },
  },
}
</script>

<style scoped>
#parentcat-btn {
  min-height: 60px;
  background-color: #db4c3e !important;
}
.category-name {
  color: #db4c3e;
}
.back-icon .fa-level-up-alt {
  transform: rotateY(180deg);
}
</style>