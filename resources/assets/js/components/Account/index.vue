<template lang="html">
  <div class="Account-manager-container">
    <div id="modal1" class="modal modal-fixed-footer">
     <div class="modal-content">
       <h4>Edit</h4>
       <div class="input-field col s6">
          <input id="fullname" placeholder="Update name" v-model="nameUpdate = UserDetail.name" type="text" class="validate">
          <label for="fullname">Name</label>
       </div>
       <div class="input-field col s6">
          <input id="username" type="text" placeholder="Update username" v-model="usernameUpdate = UserDetail.username" class="validate">
          <label for="username">Username</label>
       </div>
       <div class="input-field col s6">
          <input id="changepass" type="password" placeholder="ignore if no changes" v-model="newPass" class="validate">
          <label for="changepass">Change pass</label>
       </div>
       <div class="input-field col s6">
          <input id="newpass" type="password" v-model="confirmPass"  placeholder="ignore if no changes" class="validate">
          <label for="newpass">Confirm-new pass</label>
       </div>
     </div>
     <div class="modal-footer">
       <a href="#" v-on:click.prevent="updateUser(UserDetail.id)" class="modal-action modal-close waves-effect waves-green btn-flat ">Update</a>
     </div>
   </div>
    <h5 class="title-of-page"><i class="material-icons">list</i> Account list</h5>
    <div class="accounts-table-container">
      <table class="centered responsive-table bordered highlight">
         <thead>
           <tr>
               <th>Name</th>
               <th>Username</th>
               <th>Role</th>
               <th>Account status</th>
               <th>actions</th>
           </tr>
         </thead>
         <tbody>
          <tr v-for="data in indexData">
            <td>{{data.name}}</td>
            <td>{{data.username}}</td>
            <td>
              <div class="switch">
                 <label>
                   judge
                   <input type="checkbox" v-on:click="setJudgeRole(data.id)" checked v-if="data.role==0">
                   <input type="checkbox" v-on:click="setAdminRole(data.id)" v-if="data.role==1">
                   <span class="lever"></span>
                   admin
                 </label>
               </div>
            </td>
            <td v-if="data.disabled==null"><a href="#" v-on:click.prevent="toggleEnable(data.id)">enabled</a></td>
            <td v-else><a href="#" class="red-text" v-on:click.prevent="toggleEnable(data.id)">disabled</a></td>
            <td><i class="material-icons modal-trigger" data-target="modal1" v-on:click="FetchUser(data.id)">border_color</i> <i class="material-icons" v-on:click="deleteAccount(data.id)">delete</i></td>
          </tr>
         </tbody>
       </table>
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
        indexData:[],
        pagination:[],
        offset:4,
        UserDetail:[],
        nameUpdate:'',
        usernameUpdate:'',
        newPass:'',
        confirmPass:'',
        roleuser:1,
      }
    },
    methods: {
        fetchIndex(page)
        {
          var vm=this;
          axios.get(`/account-index-data?page=`+page).then(function(response)
          {
            console.log(response);
            vm.indexData=response.data.data;
            vm.pagination=response.data;
          });
        },
        changepage(next){
          this.pagination.current_page = next;
          this.fetchIndex(next);
        },
        deleteAccount(id)
        {
          var vm=this;
          swal({
            title: 'Are you sure?',
            text: "Activities related to this user will also be deleted!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.value) {
              axios.delete(`/account-delete/`+id).then(function(response)
              {
                if (response.data.error!=null)
                {
                  swal(
                    'Oops...',
                    response.data.error,
                    'error'
                  )
                }else
                {
                  vm.fetchIndex(vm.pagination.current_page);
                  swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  );
                }
              });
            }
          });
        },
        FetchUser(id)
        {
          var vm=this;
          this.newPass='';
          this.confirmPass='';
          axios.get(`/account-fetch-data/`+id).then(function(response)
          {
            console.log(response);
            vm.UserDetail=response.data[0];
          });
        },
        setAdminRole(id)
        {
          var vm=this;
          axios.put(`/account-update-as-admin/`+id).then(function(response)
          {
            console.log(response);
            vm.fetchIndex(1);
            swal(
              'Role Updated!',
              response.data.name+' is now a administrator',
              'success'
            );
          }).catch(function(error)
          {
            console.log(error);
          });
        },
        setJudgeRole(id)
        {
          var vm=this;
          axios.put(`/account-update-as-judge/`+id).then(function(response)
          {
            console.log(response);
            if (response.data.error!=null)
            {
              vm.fetchIndex(1);
              swal(
                'Oops!',
                response.data.error,
                'error'
              );
            }else
            {
              vm.fetchIndex(1);
              swal(
                'Role Updated!',
                response.data.name+' is now a judge',
                'success'
              );
            }
          }).catch(function(error)
          {
            console.log(error);
          });
        },
        toggleEnable(id)
        {
          var vm = this;
          axios.put(`/account-update-enable-toggle/`+id).then(function(response)
          {
            console.log(response);
            if (response.data.error==null)
            {
              swal(
                'Status changed!',
                'status updated',
                'success'
              );
              vm.fetchIndex(1);
            }else
            {
              swal(
                'Oops!',
                response.data.error,
                'error'
              );
            }
          })
        },
        updateUser(id)
        {
          var vm=this;
          swal({
            title: 'Are you sure?',
            text: "confirm update",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.value) {
              axios.put(`/account-update/`+id,{
                name:this.nameUpdate,
                username:this.usernameUpdate,
                password:this.newPass,
                password_confirmation:this.confirmPass
              }).then(function(response)
              {
                console.log(response);
                if (response.data.error!=null)
                {
                  swal(
                    'Oops...',
                    response.data.error,
                    'error'
                  )
                }else
                {
                  swal(
                    'Updated',
                    'user`s data updated',
                    'success'
                  );
                  vm.fetchIndex(1);
                }

              }).catch(function(error)
              {
                console.log(error);
                swal(
                  'Oops...',
                  error.response.data.message,
                  'error'
                )
              })
            }
          });
        },
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
      },
    },
    created () {
      this.fetchIndex();
    },
  }
</script>
