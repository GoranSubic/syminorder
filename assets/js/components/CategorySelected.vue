<template>

  <div id="products">
    <product-card
        v-if="product.enabled === true"
        v-for="product in productsdata"
        :user_is_logged_in="user_is_logged_in"
        :key="product['@id']"
        :product="product"
        class="col-6 col-sm-4 col-md-3 col-lg-2 products category-front"
        :id="'product-' + product.id"
        :additions="additions"
    >
    </product-card>
  </div>

</template>

<script>
import axios from 'axios';
import Loading from "./Loading";
import ProductCard from "./category-list/ProductCard";

export default {
  name: "CategorySelected",
  data: function () {
    return {
      additions: [],
    }
  },
  components: {
    Loading,
    ProductCard,
  },
  props: {
    productsdata: {},
    user_is_logged_in: Boolean
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
    retrieveAdditions() {
      var urlGet = '/api/product_additions?enabled=true';
      var paramsGet = {};

      axios.get(urlGet, {
        params: paramsGet
      })
          .then(response => {
            // JSON responses are automatically parsed.
            var resp = response.data["hydra:member"];
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
  },
}
</script>

<style scoped>
</style>