<template>
  <div>
    <input
        class="form-control"
        :placeholder="Translator.trans('vuejs.searchbar.placeh_input')"
        type="search"
        v-model="searchTerm"
        @input="onInput"
    >
  </div>
</template>

<script>
import {Translator} from "../main";
import {fetchProducts} from "../services/products-service";

export default {
  name: "SearchBar",
  data: function () {
    return {
      Translator: Translator,
      searchTerm: '',
      searchTimeout: null,
    }
  },
  methods: {
    onInput() {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      this.searchTimeout = setTimeout(() => {
        this.onSearchProducts(this.searchTerm);
        this.searchTimeout = null;
      }, 200);
    },


    /**
     * Handles a change in the searchTerm provided by the search bar and fetches new products
     *
     * @param {string} term
     */
    onSearchProducts(term) {
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
        this.$emit('foundproducts', { term: response.data['hydra:member'] });
        this.$emit('searchtermfound', { term: searchTerm });
      }
    },


  },
}
</script>

<style scoped>

</style>