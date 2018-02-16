<template lang="html">
  <div class="create-event-container">
    <div class="create-event-form">
      <div class="input-field col s6">
        <i class="material-icons prefix">title</i>
        <input id="EventName" v-model="titleEvent" type="text" >
        <label for="EventName">Event Title</label>
      </div>
      <div class="criterias">
        <p v-for="criteria in allCriteria" class="z-depth-1">
          <input v-model="checkedCriteria" :value="criteria.id" type="checkbox" :id="'criteria'+criteria.id" />
          <label :for="'criteria'+criteria.id">{{criteria.name}}</label>
          <i class="material-icons" v-on:click="CriteriaRemove(criteria.id)">close</i>
        </p>
      </div>
      <div class="criteria-form-container">
        <div class="input-field col s6">
          <i class="material-icons prefix">add</i>
          <input id="add_cri" v-model="CriteriaNew" type="text" >
          <label class="active" for="add_cri">Criteria</label>
        </div>
        <button class="btn waves-effect waves-light right" v-on:click="saveCriteria()" type="submit" name="action">
          Add
        </button>
      </div>
      <button class="btn waves-effect waves-light save-event-btn" v-on:click="submitAll()" type="submit" name="action">
       Save
      </button>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  export default {
    data () {
      return {
        CriteriaNew:'',
        allCriteria:[],
        checkedCriteria:[],
        titleEvent:''
      }
    },
    methods: {
      saveCriteria()
      {
        var vm=this;
        axios.post(`/criteria-store`,{
          Name:this.CriteriaNew
        }).then(function(response)
        {
          console.log(response);
          if(response.data.error==null)
          {
              vm.getCriterias();
              Materialize.toast('Criteria added',4000);
              vm.CriteriaNew='';
          }else
          {
            Materialize.toast(response.data.error,4000);
          }
          },function(error)
          {
            console.log(error);
          });
      },
      getCriterias()
      {
        var vm= this;
        axios.get(`/criteria-index`).then(function(response)
        {
          console.log(response);
          vm.allCriteria=response.data;
        })
      },
      submitAll()
      {
        var vm=this;
        axios.post(`/events-save`,{

          Title:this.titleEvent,
          Criterias:this.checkedCriteria

        }).then(function(response)
        {
          vm.titleEvent='';
          vm.checkedCriteria=[];
          console.log(response);
          Materialize.toast('Event successfully created',4000);
        },function(error)
        {
          console.log(error);
          if (error.response.data.errors.Title!=null)
          {
            Materialize.toast(error.response.data.errors.Title[0],4000);
          }
          if (error.response.data.errors.Criterias!=null)
          {
            Materialize.toast(error.response.data.errors.Criterias[0],4000);
          }
        });
      },
      CriteriaRemove(id)
      {
        var vm=this;
        if (confirm('Are you sure?')) {
          axios.delete(`/criteria-delete/`+id).then(function(response)
          {
            console.log(response);
            if (response.data.error!=null)
            {
              Materialize.toast(response.data.error,4000);
            }else
            {
              vm.getCriterias();
              Materialize.toast('criteria removed',4000);
            }
          });
        }
      }
    },
    created () {
      this.getCriterias();
    },
  }
</script>

<style lang="css">
</style>
