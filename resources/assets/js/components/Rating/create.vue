<template lang="html">
<div class="Rating-Create-Container">
  <div class="found-container" v-if="Activity.Name!=null">
    <h5>ACTIVITY: {{Activity.Name}}</h5>
    <h6>EVENT: {{EventCrit.title}}</h6>
    <div class="rating-navs">
      <a class="btn-floating btn-large waves-effect waves-light blue darken-1" v-on:click="backBtn()"><i class="material-icons">arrow_back</i></a>
      <a class="btn-floating btn-large waves-effect waves-light blue darken-1" v-on:click="nextBtn()"><i class="material-icons">arrow_forward</i></a>
    </div>
    <div class="rating-table-container z-depth-1">
      <div class="progress" v-if="Loading">
        <div class="indeterminate"></div>
      </div>
      <div class="rating-table-padding">
        <div class="rating-card-title">
          <h5><i class="material-icons">format_list_numbered</i> Scoring card</h5>
          <h5 class="contestant-number">Contestant No. {{CurrentContestant}}</h5>
        </div>
        <div class="divider">
        </div>
        <table v-if="contestantData!=null">
          <tr v-for="(criteria,loop) in EventCrit.criteria">
            <th>{{criteria.name}}</th>
            <td><input v-if="contestantData[loop]" type="number"step="0.01" v-model="updateRates[loop] = contestantData[loop].rate"></td>
          </tr>
          <tr>
            <th>Total avarage:</th>
            <td><h5 class="right">{{Average}}%</h5></td>
          </tr>
          <tr>
            <th></th>
            <td><a v-on:click="update()" class="btn right waves-effect blue darken-1 waves-light">Update</a></td>
          </tr>
        </table>
        <table v-else>
          <tr v-for="(criteria,index) in EventCrit.criteria">
            <th>{{criteria.name}}</th>
            <td><input type="number"step="0.01" v-model="Rates[index]"></td>
          </tr>
          <tr>
            <th>Total avarage:</th>
            <td><h5 class="right">0%</h5></td>
          </tr>
          <tr>
            <th></th>
            <td><a v-on:click="save()" class="btn right waves-effect blue darken-1 waves-light">Save</a></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div v-else class="not-found">
    <h5 class="grey-text"><i class="material-icons">close</i> No Activity event found</h5>
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
      CurrentContestant:1,
      contestantData:[],
      updateRates:[],
      Average:0,
      minimum:75,
      Loading:false,
      Absent:''
      }
    },
    props: [],
    methods: {
      fetchData()
      {
        var vm=this;
        axios.get(`/rating-create-data`).then(function(response)
        {
          console.log(response);

          if (response.data.activity==null)
          {
            swal(
              'Oops...',
              'No Activity event is set for you!',
              'error'
            )
          }else
          {
            vm.Activity=response.data.activity[0];
            vm.EventCrit=response.data.eventCriteria;
            vm.getcontestant();
          }
        });
      },
      save()
      {
        var vm=this;
        axios.post(`/rating-store`,{
          setUp:this.Activity.id,
          Crit:this.EventCrit.criteria,
          Rates:this.Rates,
          Contestant:this.CurrentContestant,
          Absent:this.Absent
        }).then(function(response)
        {
          if (response.data.error!=null)
          {
            swal(
              'Oops...',
              response.data.error,
              'error'
            );
          }else
          {
            swal(
              'Success!',
              'Submitted successfully!',
              'success'
            );
            console.log(response);
            vm.Rates=[];
            vm.getcontestant();
          }
        },function(error)
        {
          console.log(error);
          swal(
            'Ooops!',
            error.response.data.message,
            'error'
          )
        });
      },
      getcontestant()
      {
        this.Loading=true;
        var vm=this;
        axios.get(`/rating-get-data/`+this.Activity.id+`/`+this.CurrentContestant).then(function(response)
        {
          console.log(response);
          vm.contestantData = response.data.ratings;
          vm.Average=response.data.average;
          vm.Loading=false;
        });
      },
      nextBtn()
      {
        if (this.Activity.NumberContestant==this.CurrentContestant)
        {
          return false;
        }else if (this.contestantData==null)
        {
          swal({
            title: 'Why?',
            text: "This contestant is absent?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed'
          }).then((result) => {
            if (result.value) {
              this.updateRates=[];
              this.Rates=[];
              this.Absent='0';
              this.save();
              this.CurrentContestant++;
              this.getcontestant();
            }
          })
        }else
        {
          this.updateRates=[];
          this.Rates=[];
          this.CurrentContestant++;
          this.getcontestant();
        }
      },
      backBtn()
      {
        if (1==this.CurrentContestant)
        {
          return false;
        }else
        {
          this.updateRates=[];
          this.Rates=[];
          this.CurrentContestant--;
          this.getcontestant();
        }
      },
      update()
      {
        var vm=this;
        axios.put(`/rating-update/`+this.Activity.id,{
          Contestant:this.CurrentContestant,
          UpdateRates:this.updateRates,
          Criterias:this.EventCrit.criteria
        }).then(function(response)
        {
          if (response.data.error!=null)
          {
            vm.updateRates=[];
            vm.Rates=[];
            swal(
              'Oops...',
               response.data.error,
              'error'
            );
            vm.getcontestant();
          }else
          {
            vm.updateRates=[];
            vm.Rates=[];
            swal(
              'Updated!',
              'Updated successfully!',
              'success'
            );
            console.log(response);
            vm.getcontestant();
          }
        })
      }
    },
    created () {
      this.fetchData();
    },
  }
</script>
