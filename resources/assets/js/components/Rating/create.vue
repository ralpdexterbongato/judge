<template lang="html">
<div class="Rating-Create-Container">
  <div class="found-container" v-if="Activity.Name!=null">
    <h5>{{Activity.Name}}</h5>
    <h6>{{theEvent.title}}</h6>
    <div class="prediction-wrap">
      <a href="#" v-if="IsDone==0" v-on:click.prevent="getRank()" onclick="$('#rank-modal').modal('open');" class=" predictionOpener">RANKING</a>
    </div>
    <div class="rating-navs">
      <div class="">
        <a class="btn-floating btn-small waves-effect waves-light blue darken-1" :class="[currentContestantPage!=1?'':'disabled']" v-on:click="backBtn()"><i class="material-icons">arrow_back</i></a>
      </div>
      <div class="">
        <a class="btn-floating btn-small waves-effect waves-light blue darken-1" :class="[currentContestantPage!= contestantLastPage?'':'disabled']" v-on:click="nextBtn()"><i class="material-icons">arrow_forward</i></a>
      </div>
    </div>
    <div class="rating-table-container z-depth-1">
      <div class="progress" v-if="Loading">
        <div class="indeterminate"></div>
      </div>
      <div class="rating-table-padding">
        <div class="rating-card-title">
          <p>{{contestantData.name}}</p>
          <h6 onclick="$('#picture-prev-modal').modal('open')"><img v-if="contestantData.picture!=null" :src="'/storage/images/'+contestantData.picture" alt=""></h6>
        </div>
        <div class="divider">
        </div>
        <table v-if="contestantData.ratings!=''">
          <tr v-for="(criteria,loop) in EventCrit">
            <th>{{criteria.name}}</th>
            <td class="relativetd">
              <input min="1" v-if="contestantData.ratings!=null" :max="criteria.pivot.percentjudging" class="validate" type="number"step="1" v-model="updateRates[loop]">

              <p class="max-rating">Max - {{criteria.pivot.percentjudging}}%</p>
            </td>
          </tr>
          <tr>
            <th>Total rating:</th>
            <td><h5 class="right">{{TotalRateUpdate}}%</h5></td>
          </tr>
          <tr>
            <th></th>
            <td><a v-on:click="update()" class="btn right waves-effect blue darken-1 waves-light">Update</a></td>
          </tr>
        </table>
        <table v-else>
          <tr v-for="(criteria,index) in EventCrit">
            <th>{{criteria.name}}</th>
            <td class="relativetd"><input class="validate" type="number"step="1" min="1" :max="criteria.pivot.percentjudging" v-model="Rates[index]"><p class="max-rating">Max - {{criteria.pivot.percentjudging}}%</p></td>
          </tr>
          <tr>
            <th>Total avarage:</th>
            <td><h5 class="right">{{TotalRates}}%</h5></td>
          </tr>
          <tr>
            <th><a href="#" class="btn btn-floating red" v-on:click.prevent="ContestantAbsent()"><i class="material-icons">close</i></a></th>
            <td><a v-on:click="save()" class="btn right waves-effect blue darken-1 waves-light">Save</a></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div v-else class="not-found">
    <h6 class="grey-text"><i class="material-icons">close</i> No event to judge found</h6>
  </div>
  <div id="picture-prev-modal" class="modal">
    <div class="modal-content">
      <h5>{{contestantData.name}}</h5>
      <div class="image-wrapper">
        <img v-if="contestantData.picture!=null" :src="'/storage/images/'+contestantData.picture" alt="">
      </div>
    </div>
  </div>
  <div id="rank-modal" class="modal">
    <div class="modal-content">
      <h5>Ranking</h5>
      <p>This ranking is according to the scores you have submitted</p>
      <div class="rank-predict-table-container">
        <table>
          <tr>
            <th>Rank</th>
            <th>Contestants</th>
            <th>Rating</th>
          </tr>
          <tr v-for="(rank,rankloop) in predictionRank">
            <td>
              <span v-if="rank.place-1 == 0"><i class="material-icons">star</i></span>
              <span v-else>{{rank.place-1}}</span>
            </td>
            <td>{{rank.name}}</td>
            <td>{{rank.totalrate}}%</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" v-on:click.prevent class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
  export default {
    data () { return {
      Activity:[],
      EventCrit:[],
      Rates:[],
      currentContestantPage:1,
      contestantLastPage:1,
      contestantData:[],
      updateRates:[],
      TotalRates:0,
      minimum:75,
      Loading:false,
      Absent:'',
      theEvent:[],
      TotalRateUpdate:0,
      predictionRank:[],
      IsDone:1,
      }
    },
    props: [],
    methods: {
      getRank()
      {
        var vm=this;
        axios.get(`/get-own-score-rank/`+this.Activity.id).then(function(response)
        {
          console.log(response);
          vm.predictionRank=response.data;
        }).catch(function(error)
        {
          console.log(error);
        });
      },
      fetchData()
      {
        var vm=this;
        axios.get(`/rating-create-data`).then(function(response)
        {
          console.log(response);

          if (response.data.activity==null)
          {
            if (response.data.error!=null)
            {
              Materialize.toast(response.data.error,4000);
            }else
            {
              Materialize.toast('no event setup found',4000);
            }
          }else
          {
            vm.Activity=response.data.activity;
            vm.EventCrit=response.data.eventCriteria;
            vm.theEvent = response.data.event[0];
            vm.getcontestant(vm.currentContestantPage);
            vm.checkifDone();
          }
        });

      },
      checkifDone()
      {
        var vm=this;
        axios.get(`/check-if-done/`+vm.Activity.id).then(function(response)
        {
          console.log(response);
          vm.IsDone = response.data.response;
        }).catch(function(error)
        {
          console.log(error);
        });
      },
      save()
      {
        for (var i = 0; i < this.EventCrit.length; i++)
        {
          if (((this.Rates[i] > this.EventCrit[i].pivot.percentjudging) || (this.Rates[i] < 1) || (this.Rates[i]==null) || (this.Rates[i]=='') || (isNaN(this.Rates[i])==true))&&(this.Absent!='0'))
          {
            return false;
          }
        }
        var vm=this;
        axios.post(`/rating-store`,{
          setUp:this.Activity.id,
          Crit:this.EventCrit,
          Rates:this.Rates,
          Contestant:this.contestantData.id,
          Absent:this.Absent
        }).then(function(response)
        {
          if (response.data.error!=null)
          {
            Materialize.toast(response.data.error,4000);
          }else
          {
            Materialize.toast('Sumitted successfully',4000);
            console.log(response);
            vm.Rates=[];
            vm.Absent='';
            vm.getcontestant(vm.currentContestantPage);
            vm.checkifDone();
          }
        },function(error)
        {
          console.log(error);
          Materialize.toast(error.response.data.message,4000);
        });
      },
      getcontestant(page)
      {
        this.Loading=true;
        var vm=this;
        axios.get(`/rating-get-data/`+this.Activity.id+`?page=`+page).then(function(response)
        {
          console.log(response);
          vm.contestantData = response.data.data[0];
          vm.currentContestantPage = response.data.current_page;
          vm.contestantLastPage = response.data.last_page;
          for (var i = 0; i < vm.EventCrit.length; i++) {
            vm.Rates[i] = 0;
            if (response.data.data[0].ratings!='')
            {
              vm.updateRates[i] =response.data.data[0].ratings[i].rate;
            }

          }
          vm.Loading=false;
          vm.AddUpdatableRates();
        });
      },
      nextBtn()
      {
      if (this.contestantData.ratings=='')
        {
          if (confirm('This contestant is absent?'))
          {
            this.Absent='0';
            this.save();
          }
        }else
        {
          if (this.currentContestantPage < this.contestantLastPage)
          {
            var nextpages=this.currentContestantPage+1;
            this.getcontestant(nextpages);
          }
        }
      },
      backBtn()
      {
        if (this.currentContestantPage > 1)
        {
          this.getcontestant(this.currentContestantPage - 1);
        }
      },
      update()
      {
        for (var i = 0; i < this.EventCrit.length; i++)
        {
          if ((this.updateRates[i] > this.EventCrit[i].pivot.percentjudging) || (this.updateRates[i] < 1) || (this.updateRates[i]==null) || (this.updateRates[i]=='') || (isNaN(this.updateRates[i])==true))
          {
            return false;
          }
        }
        var vm=this;
        axios.put(`/rating-update/`+this.Activity.id,{
          Contestant:this.contestantData.id,
          UpdateRates:this.updateRates,
          Criterias:this.EventCrit
        }).then(function(response)
        {
          if (response.data.error!=null)
          {
            vm.updateRates=[];
            vm.Rates=[];
            Materialize.toast(response.data.error,4000);
            vm.getcontestant(vm.currentContestantPage);
          }else
          {
            vm.updateRates=[];
            vm.Rates=[];
            Materialize.toast('Updated successfully',4000);
            console.log(response);
            vm.getcontestant(vm.currentContestantPage);
          }
        })
      },
      ContestantAbsent()
      {
        if (confirm('This contestant is absent?'))
        {
          this.updateRates=[];
          this.Rates=[];
          this.Absent='0';
          this.save();
          this.getcontestant();
        }
      },
      addCriteriaRatings()
      {
        var totalval=0;
        for (var i = 0; i < this.EventCrit.length; i++)
        {
          totalval = totalval + Number(this.Rates[i]);
        }
        this.TotalRates = totalval;
      },
      AddUpdatableRates()
      {
        var totalval=0;
        for (var i = 0; i < 5; i++)
        {
          if (isNaN(this.updateRates[i]) == false)
          {
            totalval = totalval + Number(this.updateRates[i]);
          }else {
            totalval = totalval + Number(0);
          }
        }
        this.TotalRateUpdate= totalval;
      }
    },
    mounted () {
      this.fetchData();

    },
  }
</script>
