<template lang="html">
  <div class="setup-form">
    <!-- <form action="/setup-store" method="post"> -->
      <div class="other-activity-form-top z-depth-1 blue darken-1 white-text text-darken-3">
        <div class="input-field col s6">
          <i class="material-icons prefix">local_activity</i>
          <input id="icon_prefix" name="NameActivity" placeholder="..." v-model="ActivityName" type="text" class="white-text text-darken-3">
          <label class="white-text text-darken-3" for="icon_prefix">Activity</label>
        </div>
        <div class="input-field col s12">
            <i class="material-icons prefix">title</i>
            <input disabled class="white-text text-darken-3" :value="eventdata.title" id="disabled" type="text">
            <label class="white-text text-darken-3" for="disabled">Event type</label>
        </div>
        <div class="input-field col s12">
          <i class="material-icons prefix">person</i>
          <select  id="selectedjudge" multiple>
            <option value="" disabled selected>List</option>
            <option v-for="judge in judges" :value="judge.id">{{judge.name}}</option>
          </select>
          <label class="white-text text-darken-3">Select from judges</label>
        </div>
      </div>
      <div class="criteria-percentage-wrap z-depth-1 blue darken-1 white-text text-darken-3">
        <div class="used-pecentage">
          <p>Total</p>
          <h5 :class="[total==100?'':'red-text text-lighten-4']">{{total}}%
            <i class="material-icons white-text" v-if="total == 100">check</i>
            <i class="material-icons white-text" v-else >close</i>
          </h5>
        </div>
        <p>
          <div class="criteria-and-percent-container" v-for="(criteria,key) in eventdata.criteria">
            <div class="input-field col s6">
              <i class="material-icons prefix">%</i>
              <input placeholder="" v-on:keyup="totalPercent(key)" v-model="CriteriaPercent[key]" min="1" class="validate" type="number">
              <label class="white-text text-darken-3">{{criteria.name}}</label>
            </div>
          </div>
        </p>
      </div>
      <button class="btn waves-effect waves-light right" v-on:click="submitAll()" type="submit" name="action">
        Save
      </button>
  </div>
</template>

<script>
  export default {
    data () { return {
        ActivityName:'',
        CriteriaPercent:[],
        total:0,
      }
    },
    props: ['eventdata','judges','eventid'],
    computed: {

    },
    mounted()
    {
      this.addingDefault()
    },
    methods: {
      addingDefault()
      {
        for (var i = 0; i < this.eventdata.criteria.length; i++) {
          this.CriteriaPercent[i] = 0;
        }
      },
      totalPercent(key)
      {
        if (isNaN(this.CriteriaPercent[key]) == false)
        {
        var added=0;
        for (var i = 0; i < this.eventdata.criteria.length; i++) {
          added = added + Number(this.CriteriaPercent[i]);
        }
        this.total = added;
        }
      },
      submitAll()
      {
        if (confirm('Confirm save?'))
        {
          if (this.total!=100)
          {
            console.log('error1');
            return false;
          }
          for (var i = 0; i < this.eventdata.criteria.length; i++) {
            if ((isNaN(this.CriteriaPercent[i]) == true) || (this.CriteriaPercent[i]==null) || (this.CriteriaPercent[i]==0) || (this.CriteriaPercent[i] < 1) ||( this.CriteriaPercent[i] > 100))
            {
              console.log('error2');
              return false;
            }
          }

          var vm=this;
          axios.post(`/setup-store`,{
            NameActivity:this.ActivityName,
            judges:$('#selectedjudge').val(),
            eventid:this.eventid.id,
            percent:this.CriteriaPercent,
            criterias:this.eventdata.criteria,
          }).then(function(response)
          {
            swal(
              'New',
              'setup created!',
              'success'
            );
            console.log(response);
            window.location.href= window.location.href;
          }).catch(function(error)
          {
            console.log(error);
          });
        }
      }
    },
  }
</script>
