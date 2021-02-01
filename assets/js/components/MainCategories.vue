<template>
<div id="categories">
  <div id="main-categories">
    <div class="categories container">

      <div class="row">
        <div class="col-12 wait-for">
          <div class="mt-4">
            <loading v-show="categories.length === 0" />
          </div>
        </div>
        <category-card
            v-for="category in categories"
            :key="'/api/categories/' + category.id"
            :category="category"
            :getSubCategoriesParent="getSubCategories"
        >
        </category-card>
      </div>
    </div>
  </div>

  <category-list
      :categories="categories"
      :parentcatdata="parentcatdata"
      :categoriesdata="categoriesdata"
      :productsdata="productsdata"
      :subcategoriesdata="subcategoriesdata"
      :getSubCategoriesParent="getSubCategories"
      :user_is_logged_in="user_is_logged_in"
      plussign="plussign"
      minussign="minussign"
  >

  </category-list>

</div>
</template>

<script>
import axios from 'axios';
import Loading from "./Loading";
import CategoryList from "./category-list"
import CategoryCard from "./category-list/CategoryCard";

export default {
  name: "MainCategories",
  data: function () {
    return {
      parentcatdata: {},
      categoriesdata: {},
      productsdata: [],
      subcategoriesdata: [],
    }
  },
  components: {
    Loading,
    CategoryList,
    CategoryCard,
  },
  props: {
    categories: {
      type: Array,
      required: true,
    },
    user_is_logged_in: Boolean,
    "plussign": String,
    "minussign": String,
  },
  methods: {
    async getSubCategories(id) {
      var url = '/api/categories/' + id;
      var paramsGet = {};
      paramsGet['enabled'] = true;
      const response = await axios.get(url, {
        params: paramsGet
      });
      this.categoriesdata = response.data;

      this.productsdata = [];
      if (this.categoriesdata['products'].length) {
        var products = this.categoriesdata['products'];
        products.forEach(prod => {
          this.productsdata.push(prod);
        });
      }

      if (this.categoriesdata['children'].length) {
        this.subcategoriesdata = this.categoriesdata['children'];
        if (this.productsdata.length === 0 && this.subcategoriesdata[0]['products'].length) {
          var products = this.subcategoriesdata[0]['products'];
          products.forEach(prod => {
            this.productsdata.push(prod);
          });
        }
      } else {
        this.subcategoriesdata = [];
      }

      if (this.categoriesdata['parent'] && this.categoriesdata['parent']['name'] !== "Home") {
        this.parentcatdata = this.categoriesdata['parent'];
      } else {
        this.parentcatdata = null;
      }
    },
  },
  mounted: function () {
    this.getSubCategories(this.categories['0'].id);
  },
}
</script>

<style scoped>

</style>