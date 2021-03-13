<template>
<div id="tagservices">
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

  <tag-services-list
      class="tagservices container"
      :tagservices="tagservices"
      :tagservicesdata="tagservicesdata"
      :productsdata="productsdata"
      :user_is_logged_in="user_is_logged_in"
      :additions="additions"
      :searchtermfound="searchTermFound"
      :getTagServicesParent="getTagServices"
  >

  </tag-services-list>

</div>
</template>

<script>
import axios from 'axios';
import Loading from "./Loading";
import TagServicesList from "./tagservices-list"
import TagServicesCard from "./tagservices-list/TagServicesCard";
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
    TagServicesList,
    TagServicesCard,
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
#tagservices .searchcat {
  display: flex;
  justify-content: center;
}
#tagservices .searchbar {
  margin: 20px;
  width: 75%;
}
</style>