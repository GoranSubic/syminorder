<template>
<div id="categories">
  <div id="main-categories">
    <div class="categories container">
      <div class="row">
        <div class="col-12">
          <search-bar
              @search-products="onSearchProducts"
          >

          </search-bar>
        </div>
      </div>
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
      :additions="additions"
      :searchtermfound="searchTermFound"
  >

  </category-list>

</div>
</template>

<script>
import axios from 'axios';
import Loading from "./Loading";
import CategoryList from "./category-list"
import CategoryCard from "./category-list/CategoryCard";
import SearchBar from './SearchBar';
import { fetchProducts } from "../services/products-service";

export default {
  name: "MainCategories",
  data: function () {
    return {
      parentcatdata: {},
      categoriesdata: {},
      productsdata: [],
      subcategoriesdata: [],
      additions: [],
      searchTermFound: '',
    }
  },
  components: {
    Loading,
    CategoryList,
    CategoryCard,
    SearchBar,
  },
  props: {
    categories: {
      type: Array,
      required: true,
    },
    user_is_logged_in: Boolean,
  },
  methods: {
    /**
     * Handles a change in the searchTerm provided by the search bar and fetches new products
     *
     * @param {string} term
     */
    onSearchProducts({term}) {
      if (term !== null && term !== '' && term.length > 2) this.loadProducts(term);
    },
    async loadProducts(searchTerm) {
      // this.loading = true;
      let response;
      var currentCategoryId = null;
      try {
        response = await fetchProducts(currentCategoryId, searchTerm);
        // this.loading = false;
      } catch (e) {
        // this.loading = false;
        return;
      }
      var rspdata = response.data['hydra:member'];
      if (rspdata.length) {
        this.productsdata = response.data['hydra:member'];
        this.searchTermFound = searchTerm;
      }
    },
    async getSubCategories(id) {
      this.productsdata = [];
      this.searchTermFound = '';

      var url = '/api/categories/' + id;
      var paramsGet = {};
      paramsGet['enabled'] = true;
      const response = await axios.get(url, {
        params: paramsGet
      });
      this.categoriesdata = response.data;

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
    retrieveAdditions() {
      var urlGet = 'api/product_additions?enabled=true';
      var paramsGet = {};

      axios.get(urlGet, {
        params: paramsGet
      })
          .then(response => {
            // JSON responses are automatically parsed.
            var resp = response.data["hydra:member"];
            /*if (resp.length > 0) {
              this.additions.push(
                  { value: {}, text: 'Dodaci' },
              )
            }*/
            resp.forEach(addition => {
              var obj = {
                text: addition.name,
                code: addition.code
              };

              this.additions.push(obj);
            });
          })
          .catch(e => {
            this.errors.push(e)
          });
    },
  },
  mounted: function () {
    if (this.user_is_logged_in) this.retrieveAdditions();
    this.getSubCategories(this.categories['0'].id);
  },
}
</script>

<style scoped>

</style>