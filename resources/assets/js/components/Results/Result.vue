<template lang="html">
  <div class="results-vue">
    <div class="ranking-table-container">
      <table class="striped responsive-table">
        <thead>
          <tr>
            <th>Ranking</th>
            <th>Contestant</th>
            <th>Average</th>
            <th>Reveal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(displayrank,key) in sessionrank">
            <th v-if="displayrank.place-1 == 0"><i class="material-icons">grade</i> Winner</th>
            <th v-else>{{displayrank.place-1}} runner up</th>
            <td class="contestant-name">
              <span v-if="Reveal[key]==true">{{displayrank.contestant[0].name}}</span>
              <span v-else><i class="material-icons">lock</i></span>
            </td>
            <td><span class="bold">{{displayrank.totalRate}}%</span></td>
            <td>
              <form action="#">
                 <p>
                   <input v-model="Reveal[key]" v-on:click="[key== 0?start():'']" type="checkbox" :id="'reveal'+key" />
                   <label :for="'reveal'+key"></label>
                 </p>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="showbtn blue-text">
      <p v-if="showmore ==false" v-on:click="showmore =true"><i class="material-icons">more</i> show more</p>
      <p v-if="showmore ==true" v-on:click="showmore =false"><i class="material-icons">expand_less</i> show less</p>
    </div>
    <div class="results-table-container" :class="[showmore==true?'show':'']">
      <table class="striped responsive-table">
        <thead>
          <tr>
            <th>Contestant</th>
              <th v-for="judge in judges">{{judge.user.name}}</th>
            <th>Avarage</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(contestant,i) in allcontestant">
            <td>{{contestant.name}}</td>
            <td v-for="(judge,jkey) in judges">{{preavg[jkey][i].total}} %</td>
              <td class="bold">{{totalavg[i].total/numberofjudges}} %</td>
            <td><span class="bold"></span></td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script>
import VueConfetti from 'vue-confetti'
Vue.use(VueConfetti)
  export default {
      data () { return {
        showmore:false,
        Reveal:[],
      }
    },
    props: ['sessionrank','judges','allcontestant','preavg','totalavg','numberofjudges'],
    methods: {
      start () {
        this.$confetti.start({
          shape: 'rect',
        });
        setTimeout(() => this.$confetti.stop(), 1000);
      },
      stop () {
        this.$confetti.stop()
      },

    },
  }
</script>
