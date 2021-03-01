<template>
  <agile
      :dots="true" :nav-buttons="true"
      :mobile-first="true" :infinite="true"
      :autoplay="true" :autoplay-speed="4000" :speed="2000" :fade="true"

  >
    <div class="slide">
<!--      <h3>slide 1</h3>-->
      <img :src="img1" alt="slide1">
    </div>

    <div class="slide">
<!--      <h3>slide 2</h3>-->
      <img :src="img2" alt="slide2">
    </div>

    <div class="slide">
<!--      <h3>slide 3</h3>-->
      <img :src="img3" alt="slide3">
    </div>

    <template slot="prevButton">
      <i class="fas fa-chevron-left"></i>
    </template>
    <template slot="nextButton">
      <i class="fas fa-chevron-right"></i>
    </template>

  </agile>
</template>

<script>
import { VueAgile } from 'vue-agile'

export default {
  name: "MainCarousel",
  components: {
    agile: VueAgile
  },
  props: {
    img1: String,
    img2: String,
    img3: String,
  },
  data () {
    return {
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

</style>