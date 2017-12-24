<template lang="html">
<div class="">
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
          <div class="setup-btns-container">
            <a href="#" v-if="data.isActive==null" v-on:click.prevent="updateEnable(data.id)">Disabled</a>
            <a href="#" v-else class="green-text" v-on:click.prevent="updateDisable(data.id)">Enabled</a>
            <div class="">
              <a :href="'/results-show/'+data.id" class="btn btn-floating"><i class="material-icons white-text">remove_red_eye</i></a>
              <a href="#" class="btn btn-floating" v-on:click="Delete(data.id)"><i class="material-icons white-text">delete</i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <ul class="pagination">
      <li class="waves-effect" v-if="pagination.current_page > 1"><a href="#" @click.prevent="changepage(pagination.current_page - 1)"><i class="material-icons">chevron_left</i></a></li>
      <li v-for="page in pagesNumber" v-bind:class="[ page == isActive ? 'active blue':'']"><a href="#!" @click.prevent="changepage(page)">{{page}}</a></li>
      <li class="waves-effect" v-if="pagination.current_page < pagination.last_page"><a href="#!" @click.prevent="changepage(pagination.current_page + 1)"><i class="material-icons">chevron_right</i></a></li>
  </ul>
</div>
</template>

<script>
import axios from 'axios';
  export default {
    data () { return {
      IndexData:[],
      pagination:[],
      offset:4
      }
    },
    // props: [],
    methods: {
      FetchData(page)
      {
        var vm=this;
        axios.get(`/setup-index-data?page=`+page).then(function(response)
        {
          console.log(response);
          vm.IndexData=response.data.data;
          vm.pagination=response.data;
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
          confirmButtonText: 'Yes!'
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
      changepage(next){
        this.pagination.current_page = next;
        this.FetchData(next);
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
            confirmButtonText: 'Yes!'
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
    computed:{
      isActive:function(){
        return this.pagination.current_page;
      },
      pagesNumber:function(){
        if (!this.pagination.to) {
           return [];
        }
        var from = this.pagination.current_page - this.offset;
        if (from < 1) {
            from = 1;
        }
        var to = from + (this.offset * 2);
        if (to >= this.pagination.last_page) {
            to = this.pagination.last_page;
        }
        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }
        return pagesArray;
      }
    },
  }
</script>
