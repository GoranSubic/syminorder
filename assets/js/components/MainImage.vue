<template>
  <div @scroll="scrollMe">
    <!--<div id="scrollcounter">0px</div>-->
    <div class="greet" id="headerimage" :style="headerHeight">
      <div class="greet-message">
<!--        <h2>{{ welcome }} {{ datauname }}</h2>-->
        <h2 id="mainTitle">{{ welcome }}</h2>
        <div class="subtitle" id="subtitleBox">
          <div class="sub1">{{ meal }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MainImage",
  computed: {
    welcome: function () {
      if (this.hour < 10) return "Dobro jutro";
      else if (this.hour < 12) return "Dobar dan";
      else if (this.hour < 17) return "Prijatno poslepodne";
      else return "Dobro veče";
    },
    meal: function () {
      if (this.hour < 10) return "želimo Vam prijatno jutro, pogledajte našu ponudu za doručak";
      else if (this.hour < 12) return "odaberite najbolji ručak";
      else if (this.hour < 17) return "odaberite obrok za kasni ručak ili ranu večeru";
      else return "želimo Vam prijatno veče, pogledajte našu ponudu za večeru";
    },
    date: function () { return new Date() },
    hour: function () { return this.date.getHours() },
  },
  props: {
    datauname: String,
    headerHeight: String
  },
  created: function () {
    window.addEventListener("scroll", this.scrollMe);
  },
  methods: {
    scrollMe: function () {
      //var scrolledElem = document.getElementById("scrollelm");
      var scrolledValue = window.scrollY;// scrolledElem.offsetTop - window.scrollY;
      var topElem = document.getElementById("headerimage");
      if (scrolledValue >= 100) {
        topElem.classList.add('slim');
        document.getElementById("mainTitle").classList.add('hideIt');
        document.getElementById("subtitleBox").classList.add('hideIt');
        document.getElementById("btnOutline").classList.add('hideBtn');
      } else {
        topElem.classList.remove('slim');
        document.getElementById("mainTitle").classList.remove("hideIt");
        document.getElementById("subtitleBox").classList.remove("hideIt");
        document.getElementById("btnOutline").classList.remove("hideBtn");
      }
      //var scrlCounter = document.getElementById("scrollcounter");
      //scrlCounter.innerHTML = scrolledValue + "px";
    }
  }
}
</script>


<style scoped>
.greet {
  height: 100vh; /*15vh;*/
  width: 100%;
  top: 40px;
  left: 0;
  right: 0;
  position: fixed;
  z-index: 2;
  background-image: url('../../media/greet-home.jpeg');
  /*filter: grayscale(50%);*/
  background-repeat: no-repeat;
  background-position: center center;
  background-attachment: fixed;
  background-size: cover;
  transition-duration: 0.5s;
}
.greet .greet-message {
  position: absolute;
  top: 30%;
}
.greet h2 {
  font-size: 3em;
  color: #fff;
  text-shadow: 3px 5px 5px black;
  margin: 0 0 0 5%;
  left: 0;
  right: 0;
  text-align: left;
}
.greet div.subtitle {
  font-size: 2em;
  color: #fff;
  text-shadow: 3px 5px 5px black;
  margin: 0.5% 0 0 5%;
  left: 0;
  right: 0;
  text-align: left;
}
.greet #subtitleBox, .greet #mainTitle {
  overflow: hidden;
  transition: all .5s ease-in-out;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
}
.greet #subtitleBox.hideIt, .greet #mainTitle.hideIt {
  height: 0px !important;
}
.greet.slim {
  /*height: 15vh !important;*/
  height: 0 !important;
  top: 40px;
}
/*#scrollelm {
    margin: 30vh auto 0 auto !important;
    font-size: 15px;
    line-height: 18px;
}*/
/*#scrollcounter {
    position: fixed;
    top: 40px;
    right: 40px;
    background: red;
    padding: 10px;
    color: #fff;
    z-index: 999;
}*/
</style>