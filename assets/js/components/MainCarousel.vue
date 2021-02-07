<template>
  <agile
      :dots="false" :nav-buttons="false"
      :mobile-first="true" :infinite="true"
      :autoplay-speed="3000" :speed="1000" :fade="true"

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
.carousel .slide.agile__slide.agile__slide--current.agile__slide--active {
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden
}
.slide img {
  max-height: 500px;
  flex-shrink: 0;
  /*min-width: 100%;
  min-height: 100%*/
}
</style>