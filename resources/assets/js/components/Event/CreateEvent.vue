<template lang="html">
  <div class="create-event-container">
    <div class="create-event-form">
      <div class="input-field col s6">
        <i class="material-icons prefix">title</i>
        <input id="EventName" v-model="titleEvent" type="text" class="validate">
        <label for="EventName">Event Title</label>
      </div>
      <div class="criterias">
        <p v-for="criteria in allCriteria" class="z-depth-1">
          <input v-model="checkedCriteria" :value="criteria.id" type="checkbox" :id="'criteria'+criteria.id" />
          <label :for="'criteria'+criteria.id">{{criteria.name}}</label>
          <i class="material-icons" v-on:click="CriteriaRemove(criteria.id)">close</i>
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
              vm.$swal(
                'New',
                'Criteria added!',
                'success'
              );
              vm.CriteriaNew='';
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
            vm.$swal(
              'Sorry',
              error.response.data.message,
              'error'
            );
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
          vm.$swal(
            'New',
            'event created!',
            'success'
          );
        },function(error)
        {
          console.log(error);
          vm.$swal(
            'Sorry',
            error.response.data.message,
            'error'
          );
        });
      },
      CriteriaRemove(id)
      {
        var vm=this;
        swal({
          title: 'Are you sure?',
          text: "Please confirm",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm'
        }).then((result) => {
          if (result.value) {
            axios.delete(`/criteria-delete/`+id).then(function(response)
            {
              console.log(response);
              if (response.data.error!=null)
              {
                vm.$swal(
                  'Sorry',
                  response.data.error,
                  'error'
                );
              }else
              {
                vm.getCriterias();
                swal(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                );
              }
            });
          }
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
