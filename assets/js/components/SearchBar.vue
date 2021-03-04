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
        this.$emit('search-products', { term: this.searchTerm });
        this.searchTimeout = null;
      }, 200);
    },
  },
}
</script>

<style scoped>

</style>