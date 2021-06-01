<template>
  <agile
      :dots="true" :nav-buttons="true"
      :mobile-first="true" :infinite="true"
      :autoplay="true" :autoplay-speed="4000" :speed="3000" :fade="true"

  >
    <div class="slide">
      <span v-html="imgtext1"></span>
      <img :src="img1" :alt="imgtext1">
    </div>

    <div class="slide">
      <span v-html="imgtext2"></span>
      <img :src="img2" :alt="imgtext2">
    </div>

    <div class="slide">
      <span v-html="imgtext3"></span>
      <img :src="img3" :alt="imgtext3">
    </div>

    <template slot="prevButton">
      <span v-html="Translator.trans('vuejs.maincarousel.button.previous')"></span>
    </template>
    <template slot="nextButton">
      <span v-html="Translator.trans('vuejs.maincarousel.button.next')"></span>
    </template>

  </agile>
</template>

<script>
import { VueAgile } from 'vue-agile';
import {Translator} from "../main";

export default {
  name: "MainCarousel",
  components: {
    agile: VueAgile
  },
  props: {
    img1: String,
    img2: String,
    img3: String,
    img4: String,
    img5: String,
    imgtext1: String,
    imgtext2: String,
    imgtext3: String,
  },
  data () {
    return {
      Translator: Translator,
      myOptions: {
        /*  navButtons: true,
        responsive: [
           {
             breakpoint: 600,
             settings: {
               navButtons: true,
               dots: false,
               infinite: true
             }
           },
           {
             breakpoint: 900,
             settings: {
               navButtons: true,
               dots: true,
               infinite: true
             }
           }
         ]*/
      }
    }}

}
</script>

<style scoped>
.slide {
  background-color: #548fad;
}
</style>