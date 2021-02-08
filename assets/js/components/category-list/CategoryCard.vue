<template>
  <div @click="getSubCategories(category.id)"
      class="col-6 col-sm-6 col-md-4 col-lg-3 category subcategory" :id="'category-' + category.id">
    <div class="cat-border">
      <div class="col-6 cat-image">
        <img v-if="category.picture && category.picture.imageName"
            :alt="category.name"
            :src="'images/products/' + category.picture.imageName"
            class="img-fluid"
        />
        <span v-else>
          No image!
        </span>
      </div>
      <div class="col-6 cat-name">
          {{ category.name }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CategoryCard",
  props: {
    category: {
      type: Object,
      required: true,
    },
    getSubCategoriesParent: Function
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