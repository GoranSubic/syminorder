<template>
  <div @click="getTagServices(tag.id)">
    <div class="tagservice-border">
      <div class="tagservice-image">
        <img v-if="tag.picture && tag.picture.imageName"
            :alt="tag.name"
            :src="'images/products/' + tag.picture.imageName"
            class="img-fluid"
        />
        <span v-else>
          No image!
        </span>
      </div>
      <div class="tagservice-name">
          {{ tag.name }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TagServicesCard",
  props: {
    tag: {
      type: Object,
      required: true,
    },
    getTagServicesParent: Function
  },
  methods: {
    getTagServices(id) {
      setTimeout(function () {
        var elmnt = document.getElementById("about-tagservices");
        elmnt.scrollIntoView({ behavior: 'smooth', alignToTop: true });
      }, 500);

      this.getTagServicesParent(id);
    }
  },
}
</script>

<style scoped>

</style>