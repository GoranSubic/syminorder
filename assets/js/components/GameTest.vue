<template>
<div class="game-quiz">
  <b-progress class="mt-2" :max="max" show-value>
    <b-progress-bar v-for="quest in questions" v-bind:key="quest.id"
                    :value="quest.value"
                    :label="quest.answer !== null ? quest.answer : ' '"
                    :variant="quest.answer === 'Da' ? 'warning' : 'info'"></b-progress-bar>
  </b-progress>

  <div class="game-quiz-question">
    <h4 id="question">{{ question_data }}</h4>
  </div>

  <div class="game-quiz-buttons">
    <div>
      <b-button v-if="q_num !== null && q_num >= 0 && q_num < max" class="mt-3"
                :variant="questions[q_num].answer === 'Da' ? 'warning' : 'default'"
                @click="answerYes">Da</b-button>
      <b-button v-if="q_num !== null && q_num >= 0 && q_num < max" class="mt-3"
                :variant="questions[q_num].answer === 'Ne' ? 'info' : 'default'"
                @click="answerNo">Ne</b-button>
    </div>

    <div>
      <b-button v-if="q_num !== null && q_num > 0 && q_num < max" class="mt-3" @click="prevQuestion">Prethodno pitanje</b-button>
      <b-button v-if="q_num !== null && q_num < max-1" class="mt-3"
                :disabled="questions[q_num].answer === null ? true : false"
                @click="nextQuestion">Sledeće   pitanje</b-button>

      <b-button v-if="q_num === null" class="mt-3" @click="startQuiz">Započni kviz</b-button>
      <b-button v-if="q_num !== null && q_num === max-1" class="mt-3" @click="finishQuiz">Završi / Vidi rezultate</b-button>
    </div>
  </div>
</div>
</template>

<script>
import {Translator} from "../main";

export default {
  name: "GameTest",
  data() {
    return {
      Translator: Translator,
      q_num: null,
      question_data: "Proverite da li ste zdravi :)",
      max: 12,
      questions: [
        {
          id: 0,
          title: "Da li Vam se telesna težina poveća za kratko vreme?",
          value: 0,
          answer: null,
        },
        {
          id: 1,
          title: "Da li ste povremeno neobjašnjivo depresivni?",
          value: 0,
          answer: null,
        },
        {
          id: 2,
          title: "Da li patite od zapušenog nosa, kijanja, curenja nosa?",
          value: 0,
          answer: null,
        },
        {
          id: 3,
          title: "Da li patite od zatvora ili dijareje?",
          value: 0,
          answer: null,
        },
        {
          id: 4,
          title: "Da li ste pospani posle jela?",
          value: 0,
          answer: null,
        },
        {
          id: 5,
          title: "Da li imate crvenilo kože, svrab, astmu?",
          value: 0,
          answer: null,
        },
        {
          id: 6,
          title: "Da li zadržavate tečnost, otičete?",
          value: 0,
          answer: null,
        },
        {
          id: 7,
          title: "Jeste li hronično umorni?",
          value: 0,
          answer: null,
        },
        {
          id: 8,
          title: "Da li imate bolove u stomaku?",
          value: 0,
          answer: null,
        },
        {
          id: 9,
          title: "Da li ste često prehladjeni?",
          value: 0,
          answer: null,
        },
        {
          id: 10,
          title: "Da li imate migrene, glavobolje?",
          value: 0,
          answer: null,
        },
        {
          id: 11,
          title: "Da li se nadimate posle jela?",
          value: 0,
          answer: null,
        },
      ],
    }
  },
  computed: {

  },
  methods: {
    startQuiz() {
      this.q_num = 0;
      this.question_data = this.questions[this.q_num].title;
    },
    finishQuiz() {
      this.q_num++;

      var numYes = 0;
      this.questions.forEach(function (el) {
        if (el.answer === "Da") numYes++;
      });

      var title = document.getElementById("question");
      if (numYes >= this.questions.length/3) {
        this.question_data = "Rezultat Vašeg testiranja pokazuje da je potrebno da napravite promenu, obratite nam se za savet.";
        title.classList.add('center-title');
      } else {
        this.question_data = "Rezultat Vašeg testiranja pokazuje da je sve u redu! :)";
        title.classList.add('center-title');
      }
    },
    nextQuestion() {
      if (this.q_num < this.questions.length-1) {
        this.q_num++;
        this.question_data = this.questions[this.q_num].title;
        if (this.questions[this.q_num].answer !== null) {
          this.questions[this.q_num].value = 1;
        }
      }
    },
    prevQuestion() {
      if (this.q_num > 0) {
        this.q_num--;
        this.question_data = this.questions[this.q_num].title;
        this.questions[this.q_num+1].value = 0;
      }
    },
    answerYes() {
      this.questions[this.q_num].answer = "Da";
      this.questions[this.q_num].value = 1;
    },
    answerNo() {
      this.questions[this.q_num].answer = "Ne";
      this.questions[this.q_num].value = 1;
    }
  },
}
</script>

<style scoped>
  .game-quiz {
    line-height: 1.3em;
    font-size: 1.3em;
    text-align: center;
    margin: 3% !important;
    padding: 2% !important;
    min-height: 250px;
    border: 2px solid #548fad;
    border-radius: 10px;
  }
  .game-quiz-question {
    padding: 10px 0 10px 0;
    min-height: 2em;
  }

  #question.center-title {
    padding-top: 7%;
  }
</style>