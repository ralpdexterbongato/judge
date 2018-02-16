<template lang="html">
<div class="activity-index-wrap">
  <div class="activities-setup-container">
    <div class="activities-box" v-for="data in IndexData">
      <div class="card blue darken-1">
        <div class="card-content white-text">
          <span class="card-title">{{data.Name}}</span>
          <table>
            <tr>
              <td>Event</td>
            </tr>
            <tr>
              <td>
                <div class="chip event-wrap-name">
                  {{data.event.title}}
                </div>
              </td>
            </tr>
            <tr>
              <td>Contestants:</td>
            </tr>
            <tr>
              <td class="contestant-prev-container">
                <div class="chip" v-for="contestant in data.contestants">
                  <img v-if="contestant.picture!=''" :src="'storage/images/'+contestant.picture" alt="Person">
                  {{contestant.name}}
                  <i class="remove-contestant material-icons" v-on:click="deleteContestant(contestant.id)">close</i>
                </div>
              </td>
            </tr>
            <tr>
              <td>Judges:</td>
            </tr>
            <tr>
              <td class="judges-prev-container">
                <div class="chip" v-for="judge in data.judges">
                  {{judge.name}}
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div class="card-action blue darken-2 setup-actions">
          <div class="">
            <a href="#" v-if="data.isActive==null" class="yellow-text" v-on:click.prevent="updateEnable(data.id)">Disabled</a>
            <a href="#" v-else class="white-text" v-on:click.prevent="updateDisable(data.id)">Enabled</a>
          </div>
          <div class="setup-btns-container">
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
     <a class="btn" @click="toggleShow">set image</a>
   	<img class="contestant-pic-prev" :src="imgDataUrl">
   </div>
   <div class="modal-footer">
     <a href="#" v-on:click.prevent="submitContestant()" class="modal-action modal-close waves-effect waves-green btn-flat ">Add</a>
   </div>
 </div>
 <my-upload field="img"
        @crop-success="cropSuccess"
        @crop-upload-success="cropUploadSuccess"
        @crop-upload-fail="cropUploadFail"
        v-model="show"
   :width="300"
   :height="300"
   :params="params"
   :headers="headers"
   img-format="png"
   langType="en"
   :noCircle="true"
   :noSquare="true"
   ></my-upload>
</div>
</template>

<script>

import axios from 'axios';
import myUpload from 'vue-image-crop-upload';
  export default {
    data () { return {
      IndexData:[],
      pagination:[],
      offset:4,
      name:'',
      currentSelectedSetup:null,
      show: false,
			params: {
				token: '123456798',
				name: 'avatar',
			},
			headers: {
				smail: '*_~'
			},
			imgDataUrl: ''
      }
    },
    components: {
			'my-upload': myUpload
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
        if (confirm('Are you sure?'))
        {
          axios.delete(`/setup-delete/`+id).then(function(response)
          {
            console.log(response);
            vm.FetchData();
            Materialize.toast('Deleted successfully',4000);
          });
        }
      },
      changepage(next){
        this.pagination.current_page = next;
        this.FetchData(next);
      },
      updateEnable(id)
      {
        var vm=this;
        if (confirm('Are you sure? other setups will be disabled'))
        {
          axios.put(`/setup-enable/`+id).then(function(response)
          {
            console.log(response);
            Materialize.toast('Enabled successfully',4000);
            vm.FetchData();
          });
        }
      },
      updateDisable(id)
      {
        var vm=this;
        if (confirm('Are you sure?')) {
          axios.put(`/setup-disable/`+id).then(function(response)
          {
            console.log(response);
            Materialize.toast('Disabled successfully',4000);
            vm.FetchData();
          });
        }
      },
        submitContestant()
        {
          var vm=this;
          axios.post(`/contestant-add/`+vm.currentSelectedSetup,{
            name:this.name,
            image:this.imgDataUrl
          }).then(function(response)
          {
            console.log(response);
            Materialize.toast('Added successfully',4000);
            vm.name='';
            vm.imgDataUrl='';
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
              Materialize.toast('removed successfully',4000);
              vm.FetchData();
            }).catch(function(error)
            {
              console.log(error);
            });
          }
        },
        toggleShow() {
				this.show = !this.show;
			},
            /**
			 * crop success
			 *
			 * [param] imgDataUrl
			 * [param] field
			 */
			cropSuccess(imgDataUrl, field){
				console.log('-------- crop success --------');
				this.imgDataUrl = imgDataUrl;
			},
			/**
			 * upload success
			 *
			 * [param] jsonData  server api return data, already json encode
			 * [param] field
			 */
			cropUploadSuccess(jsonData, field){
				console.log('-------- upload success --------');
				console.log(jsonData);
				console.log('field: ' + field);
			},
			/**
			 * upload fail
			 *
			 * [param] status    server api return error status, like 500
			 * [param] field
			 */
			cropUploadFail(status, field){
				console.log('-------- upload fail --------');
				console.log(status);
				console.log('field: ' + field);
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
