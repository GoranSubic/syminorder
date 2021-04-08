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
                @click="answerYes">{{ Translator.trans('vuejs.gametest.answer_yes') }}</b-button>
      <b-button v-if="q_num !== null && q_num >= 0 && q_num < max" class="mt-3"
                :variant="questions[q_num].answer === 'Ne' ? 'info' : 'default'"
                @click="answerNo">{{ Translator.trans('vuejs.gametest.answer_no') }}</b-button>
    </div>

    <div>
      <b-button v-if="q_num !== null && q_num > 0 && q_num < max" class="mt-3"
                variant="info"
                @click="prevQuestion">{{ Translator.trans('vuejs.gametest.prev_question') }}</b-button>
      <b-button v-if="q_num !== null && q_num < max-1" class="mt-3"
                variant="info"
                :disabled="questions[q_num].answer === null ? true : false"
                @click="nextQuestion">{{ Translator.trans('vuejs.gametest.next_question') }}</b-button>

      <b-button v-if="q_num === null" class="mt-3" variant="info"
                @click="startQuiz">{{ Translator.trans('vuejs.gametest.start') }}</b-button>
      <b-button v-if="q_num !== null && q_num === max-1" class="mt-3" variant="info"
                @click="finishQuiz">{{ Translator.trans('vuejs.gametest.finish') }}</b-button>
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
      question_data: Translator.trans('vuejs.gametest.check_your_health'),
      max: 12,
      questions: [
        {
          id: 0,
          title: Translator.trans('vuejs.gametest.questions.question1'),
          value: 0,
          answer: null,
        },
        {
          id: 1,
          title: Translator.trans('vuejs.gametest.questions.question2'),
          value: 0,
          answer: null,
        },
        {
          id: 2,
          title: Translator.trans('vuejs.gametest.questions.question3'),
          value: 0,
          answer: null,
        },
        {
          id: 3,
          title: Translator.trans('vuejs.gametest.questions.question4'),
          value: 0,
          answer: null,
        },
        {
          id: 4,
          title: Translator.trans('vuejs.gametest.questions.question5'),
          value: 0,
          answer: null,
        },
        {
          id: 5,
          title: Translator.trans('vuejs.gametest.questions.question6'),
          value: 0,
          answer: null,
        },
        {
          id: 6,
          title: Translator.trans('vuejs.gametest.questions.question7'),
          value: 0,
          answer: null,
        },
        {
          id: 7,
          title: Translator.trans('vuejs.gametest.questions.question8'),
          value: 0,
          answer: null,
        },
        {
          id: 8,
          title: Translator.trans('vuejs.gametest.questions.question9'),
          value: 0,
          answer: null,
        },
        {
          id: 9,
          title: Translator.trans('vuejs.gametest.questions.question10'),
          value: 0,
          answer: null,
        },
        {
          id: 10,
          title: Translator.trans('vuejs.gametest.questions.question11'),
          value: 0,
          answer: null,
        },
        {
          id: 11,
          title: Translator.trans('vuejs.gametest.questions.question12'),
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
        this.question_data = Translator.trans('vuejs.gametest.result_not_good');
        title.classList.add('center-title');
      } else {
        this.question_data = Translator.trans('vuejs.gametest.result_good');
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
    max-width: 800px;
    margin: auto !important;
    padding: 2% !important;
    min-height: 250px;
    /*border: 2px solid #548fad;
    border-radius: 10px;*/
  }
  .game-quiz-question {
    padding: 10px 0 10px 0;
    min-height: 2em;
    /*color: #548fad;*/
  }

  #question.center-title {
    padding-top: 7%;
  }

  button {
    border: 2px solid #548fad;
    font-weight: bold;
  }

  .btn.btn-default {
    color: #548fad;
  }
  .btn.btn-info, .btn.btn-warning {
    color: #FFFFFF;
  }
</style>