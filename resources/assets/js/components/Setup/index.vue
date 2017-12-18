<template lang="html">
  <div class="activities-setup-container">
    <div class="activities-box" v-for="data in IndexData">
      <div class="card blue darken-1">
        <div class="card-content white-text">
          <span class="card-title">{{data.Name}}</span>
          <table>
            <tr>
              <th>Event:</th>
              <td>{{data.event.title}}</td>
            </tr>
            <tr>
              <th>Contestants:</th>
              <td>{{data.NumberContestant}}</td>
            </tr>
            <tr>
              <th>Judges:</th>
            </tr>
            <tr>
              <td v-for="judge in data.judges">{{judge.name}}</td>
            </tr>
          </table>
        </div>
        <div class="card-action white setup-actions">
          <p>{{data.created_at}}</p>
          <span>
            <a href="#" v-if="data.isActive==null" v-on:click.prevent="updateEnable(data.id)">Disabled</a>
            <a href="#" v-else class="green-text" v-on:click.prevent="updateDisable(data.id)">Enabled</a>
            <a :href="'/results-show/'+data.id" class="btn btn-floating"><i class="material-icons white-text">remove_red_eye</i></a>
            <a href="#" class="btn btn-floating" v-on:click="Delete(data.id)"><i class="material-icons white-text">delete</i></a>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
  export default {
    data () { return {
      IndexData:[]
      }
    },
    // props: [],
    methods: {
      FetchData()
      {
        var vm=this;
        axios.get(`/setup-index-data`).then(function(response)
        {
          console.log(response);
          vm.IndexData=response.data.data;
        })
      },
      Delete(id)
      {
        var vm=this;
        swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            axios.delete(`/setup-delete/`+id).then(function(response)
            {
              console.log(response);
              vm.FetchData();
              swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
            })
          }
        })
      },
      updateEnable(id)
      {
        var vm=this;
        swal({
            title: 'Are you sure?',
            text: "The other enabled setup will be disabled",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,enable it!'
          }).then((result) => {
            if (result.value) {
              axios.put(`/setup-enable/`+id).then(function(response)
              {
                console.log(response);
                swal(
                  'Enabled!',
                  'Setup enabled',
                  'success'
                )
                vm.FetchData();
              });
            }
          })
      },
      updateDisable(id)
      {
        var vm=this;
        swal({
            title: 'Are you sure?',
            text: "This setup will be disabled",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,disable it!'
          }).then((result) => {
            if (result.value) {
              axios.put(`/setup-disable/`+id).then(function(response)
              {
                console.log(response);
                swal(
                  'Disabled!',
                  'Setup disabled',
                  'success'
                )
                vm.FetchData();
              });
            }
          })
      },
    },
    created () {
      this.FetchData();
    },
  }
</script>
