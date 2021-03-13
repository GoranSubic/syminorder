<template>
<div>
  <div class="row">
    <div class="col-12 wait-for">
      <div class="mt-4">
        <loading v-show="tagservices.length === 0" />
      </div>
    </div>
    <tag-services-card
        class="col-4 col-sm-4 col-md-3 col-lg-2 tagservices tagservices-card"
        :id="'tag-' + tag.id"
        v-for="tag in tagservices"
        :key="'/api/tag_services/' + tag.id"
        :tag="tag"
        :getTagServicesParent="getTagServices"
    >
    </tag-services-card>
  </div>

  <div v-if="searchtermfound !== 'undefined' && searchtermfound !== ''" class="row category-parent">
    <div class="col-12 tagservice-info">
      <h4 class="col-12 category-name text-left">{{ Translator.trans('vuejs.search_by', { 'searchtermfound': this.searchtermfound }) }}</h4>
      <!--      <div class="col-12 category-desc text-left" v-html="searchtermfound"></div>-->
    </div>
  </div>
  <div v-else class="row category-parent">
    <div class="col-10 tagservice-info">
      <h4 class="col-12 category-name text-left" v-html="tagservicesdata['name']"></h4>
      <div class="col-12 category-desc text-left" v-html="tagservicesdata['description']"></div>
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
import ProductCard from "./ProductCard"
import {Translator} from "../../main";
import TagServicesCard from "./TagServicesCard";

export default {
  name: "TagServicesList",
  data() {
    return {
      Translator: Translator,
    }
  },
  components: {
    TagServicesCard,
    Loading,
    ProductCard,
  },
  props: {
    tagservices: {
      type: Array,
      required: true,
    },
    user_is_logged_in: Boolean,
    tagservicesdata: Object,
    productsdata: Array,
    additions: {
      type: Array,
      required: false
    },
    searchtermfound: String,
    getTagServicesParent: Function
  },
  methods: {
    getTagServices(id) {
      this.getTagServicesParent(id);
    }
  },
}
</script>

<style scoped>
#parentcat-btn {
  min-height: 60px;
  background-color: #548fad !important;
}
.category-name {
  color: #548fad;
}
.back-icon .fa-level-up-alt {
  transform: rotateY(180deg);
}
</style>