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

    <div class="tagservices container">
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
    </div>

  </div>

  <div id="about-tagservices">
    <div v-if="searchtermfound !== 'undefined' && searchtermfound !== ''" class="row category-parent">
      <div class="col-12 tagservice-info">
        <h4 class="col-12 category-name text-left">{{ Translator.trans('vuejs.search_by', { 'searchtermfound': this.searchtermfound }) }}</h4>
        <!--      <div class="col-12 category-desc text-left" v-html="searchtermfound"></div>-->
      </div>
    </div>
    <div v-else class="row category-parent">
      <div class="col-10 tagservice-info">
        <a class="show-tagservice-link" :href="Routing.generate('tagservice_show_front', {slug: tagservicesdata['slug']})">
          <h4 class="col-12 category-name tagservice-title text-left" v-html="tagservicesdata['name']"></h4>
<!--          <b-button size="sm" class="learn-more" variant="info">
            {{ Translator.trans('vuejs.indications.learn_more') }}
          </b-button>-->
        </a>
        <div class="col-12 category-desc text-left" v-html="showDescByConfig"></div>
      </div>
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
            class="col-6 col-sm-4 col-md-3 col-lg-2 products"
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
import {Routing} from "../../main";
import TagServicesCard from "./TagServicesCard";
import SearchBar from '../SearchBar';

export default {
  name: "TagServicesList",
  data() {
    return {
      Translator: Translator,
      Routing: Routing,
    }
  },
  components: {
    TagServicesCard,
    Loading,
    ProductCard,
    SearchBar,
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
    getTagServicesParent: Function,
    showdescription: String,
    setFoundproductsParent: Function,
    setSearchtermfoundParent: Function,
  },
  methods: {
    getTagServices(id) {
      this.getTagServicesParent(id);
    },
    setFoundproducts({term}) {
      this.setFoundproductsParent({term});
    },
    setSearchtermfound({term}) {
      this.setSearchtermfoundParent({term});
    }
  },
  computed: {
    showDescByConfig: function () {
      if (this.showdescription !== null && this.showdescription !== "" && this.showdescription === "show_long_desc") {
        if (this.tagservicesdata['long_description'] !== null && this.tagservicesdata['long_description'] !== "") {
          return this.tagservicesdata['long_description'];
        }
      }
      return this.tagservicesdata['description'];
    }
  }
}
</script>

<style scoped>
h4.tagservice-title {
  margin: 0;
  display: inline;
}
a.show-tagservice-link:hover {
  text-decoration: none;
}

#about-tagservices {
  padding-top: 50px;
}

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