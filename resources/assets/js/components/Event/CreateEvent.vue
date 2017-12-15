<template lang="html">
  <div class="create-event-container">
    <div class="create-event-form">
      <div class="input-field col s6">
        <i class="material-icons prefix">title</i>
        <input id="EventName" type="text" class="validate">
        <label for="EventName">Event Title</label>
      </div>
      <div class="input-field col s6">
        <i class="material-icons prefix">people</i>
        <input id="contestant_no" type="number" class="validate">
        <label for="contestant_no">Number of contestants</label>
      </div>
      <div class="criterias">
        <p v-for="criteria in allCriteria">
          <input type="checkbox" :id="'criteria'+criteria.id" />
          <label :for="'criteria'+criteria.id">{{criteria.name}}</label>
        </p>
      </div>
      <div class="input-field col s6">
        <i class="material-icons prefix">add</i>
        <input id="add_cri" v-model="CriteriaNew" type="text" class="validate">
        <label class="active" for="add_cri">Add criteria</label>
        <button class="btn waves-effect waves-light right" v-on:click="saveCriteria()" type="submit" name="action">
          Add
        </button>
      </div>
      <button class="btn waves-effect waves-light save-event-btn" type="submit" name="action">
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
              vm.$swal(
                'New',
                'Criteria added!',
                'success'
              );
              vm.CriteriaNew=''
          }else
          {
            vm.$swal(
              'Sorry',
              response.data.error,
              'error'
            );
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
      }
    },
    created () {
      this.getCriterias();
    },
  }
</script>

<style lang="css">
</style>
