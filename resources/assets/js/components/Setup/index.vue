<template lang="html">
<div class="activity-index-wrap">
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
            </tr>
            <tr>
              <th></th>
              <td class="contestant-prev-container">
                <div class="chip" v-for="contestant in data.contestants">
                  <img v-if="contestant.picture!=''" :src="'storage/images/'+contestant.picture" alt="Person">
                  {{contestant.name}}
                  <i class="remove-contestant material-icons" v-on:click="deleteContestant(contestant.id)">close</i>
                </div>
              </td>
            </tr>
            <tr>
              <th>Judges:</th>
            </tr>
            <tr>
              <th></th>
              <td class="judges-prev-container">
                <div class="chip" v-for="judge in data.judges">
                  {{judge.name}}
                </div>
              </td>
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
              <a href="#" class="btn btn-floating" v-on:click.prevent="Delete(data.id)"><i class="material-icons white-text">delete</i></a>
              <a href="#" class="btn btn-floating" v-on:click.prevent="currentSelectedSetup = data.id" onclick="$('#contestantmodal').modal('open');" ><i class="material-icons white-text">person_add</i></a>
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
  <div id="contestantmodal" class="modal modal-fixed-footer">
   <div class="modal-content">
     <h5>Add Contestant</h5>
     <div class="input-field col s6">
        <input id="name" v-model="name" type="text">
        <label for="name">Name/Group</label>
     </div>
      <input type="file" @change="onFileChange">
      <img class="prev-image" :src="image" alt="">
   </div>
   <div class="modal-footer">
     <a href="#" v-on:click.prevent="submitContestant()" class="modal-action modal-close waves-effect waves-green btn-flat ">Add</a>
   </div>
 </div>
</div>
</template>

<script>
import axios from 'axios';
  export default {
    data () { return {
      IndexData:[],
      pagination:[],
      offset:4,
      image:'',
      name:'',
      currentSelectedSetup:null,
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
      onFileChange(e) {
          var files = e.target.files || e.dataTransfer.files;
          if (!files.length)
            return;
          this.createImage(files[0]);
        },
        createImage(file) {
          var image = new Image();
          var reader = new FileReader();
          var vm = this;

          reader.onload = (e) => {
            vm.image = e.target.result;
          };
          reader.readAsDataURL(file);
        },
        submitContestant()
        {
          var vm=this;
          axios.post(`/contestant-add/`+vm.currentSelectedSetup,{
            name:this.name,
            image:this.image
          }).then(function(response)
          {
            console.log(response);
            swal(
              'Contestant',
              'Added sucessfully',
              'success'
            );
            vm.FetchData();
          }).then(function(error)
          {
            console.log(error);
          });
        },
        deleteContestant(id)
        {
          if (confirm('confirm remove?'))
          {
            var vm = this;
            axios.delete(`/contestant-remove/`+id).then(function(response)
            {
              console.log(response);
              swal(
                'Removed',
                'removed sucessfully',
                'success'
              );
              vm.FetchData();
            }).catch(function(error)
            {
              console.log(error);
            });
          }
        }
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
