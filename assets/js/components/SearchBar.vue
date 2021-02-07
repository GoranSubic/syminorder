<template>
  <div>
    <input
        class="form-control"
        placeholder="Pronađi obrok po nazivu..."
        type="search"
        v-model="searchTerm"
        @input="onInput"
    >
  </div>
</template>

<script>
export default {
  name: "SearchBar",
  data: function () {
    return {
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