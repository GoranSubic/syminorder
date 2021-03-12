<template>
<div id="categories">
  <div id="main-categories">
    <div class="categories container">
      <div class="row">
        <div class="col-12 searchcat">
          <search-bar
              class="searchbar"
              @foundproducts="setFoundproducts"
              @searchtermfound="setSearchtermfound"
          >

          </search-bar>
        </div>
      </div>
      <div class="row">
        <div class="col-12 wait-for">
          <div class="mt-4">
            <loading v-show="tagservices.length === 0" />
          </div>
        </div>
        <category-card
            v-for="tag in tagservices"
            :key="'/api/tag_services/' + tag.id"
            :category="tag"
            :getSubCategoriesParent="getTagServices"
        >
        </category-card>
      </div>
    </div>
  </div>

  <category-list
      :categories="tagservices"
      :categoriesdata="tagservicesdata"
      :productsdata="productsdata"
      :getSubCategoriesParent="getTagServices"
      :user_is_logged_in="user_is_logged_in"
      :additions="additions"
      :searchtermfound="searchTermFound"

      :parentcatdata=null
      :subcategoriesdata=[]
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

export default {
  name: "TagServicesMain",
  data: function () {
    return {
      tagservicesdata: {},
      productsdata: [],
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
    tagservices: {
      type: Array,
      required: true,
    },
    user_is_logged_in: Boolean,
  },
  methods: {
    setFoundproducts({term}) {
      this.productsdata = term;
    },
    setSearchtermfound({term}) {
      this.searchTermFound = term;
    },
    // async getSubCategories(id) {
    async getTagServices(id) {
      this.productsdata = [];
      this.searchTermFound = '';

      var url = '/api/tag_services/' + id;
      var paramsGet = {};
      paramsGet['enabled'] = true;
      const response = await axios.get(url, {
        params: paramsGet
      });
      this.tagservicesdata = response.data;

      if (this.tagservicesdata['products'].length) {
        var products = this.tagservicesdata['products'];
        products.forEach(prod => {
          this.productsdata.push(prod);
        });
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
    this.getTagServices(this.tagservices['0'].id);
  },
}
</script>

<style scoped>
#categories .searchcat {
  display: flex;
  justify-content: center;
}
#categories .searchbar {
  margin: 20px;
  width: 75%;
}
</style>