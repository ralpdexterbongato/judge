<template lang="html">
  <form action="">
    <div class="input-field col s6">
      <i class="material-icons prefix">account_circle</i>
      <input id="icon_prefix" v-model="name" type="text" >
      <label for="icon_prefix">Full name</label>
    </div>
    <div class="input-field col s6">
      <i class="material-icons prefix">verified_user</i>
      <input id="icon_username" v-model="username" type="text" >
      <label for="icon_username">Username</label>
    </div>
    <div class="input-field col s6">
      <i class="material-icons prefix">vpn_key</i>
      <input id="icon_pass" v-model="password" type="password" >
      <label for="icon_pass">Password</label>
    </div>
    <div class="input-field col s6">
      <i class="material-icons prefix">vpn_key</i>
      <input id="icon_confirm" v-model="password_confirmation" type="password" >
      <label for="icon_confirm">Confirm password</label>
    </div>
    <div class="row">
      <p>
      <input v-model="role" type="radio" value="0" id="role1" />
      <label for="role1">Admin</label>
      </p>
      <p>
        <input v-model="role" type="radio" value="1" id="role2" />
        <label for="role2">Judge</label>
      </p>
    </div>
    <button class="btn waves-effect waves-light right" v-on:click="submitAccount()" type="button">Submit
     <i class="material-icons right">send</i>
    </button>
  </form>
</template>

<script>
  export default {
    data () { return {
        name:'',
        username:'',
        password:'',
        password_confirmation:'',
        role:''
      }
    },
    methods:
    {
      submitAccount()
      {
        var vm = this;
        axios.post(`/register-save`,{
          name:this.name,
          username:this.username,
          password:this.password,
          password_confirmation:this.password_confirmation,
          role:this.role
        }).then(function(response)
        {
          console.log(response);
          Materialize.toast('Successfully created',4000);
          vm.name = '';
          vm.username = '';
          vm.password = '';
          vm.password_confirmation = '';
          vm.role = '';
        }).catch(function(error)
        {
          if (error.response.data.errors.name!=null)
          {
            Materialize.toast(error.response.data.errors.name[0],4000);
          }
          if (error.response.data.errors.username!=null)
          {
            Materialize.toast(error.response.data.errors.username[0],4000);
          }
          if (error.response.data.errors.password!=null)
          {
            Materialize.toast(error.response.data.errors.password[0],4000);
          }
          if (error.response.data.errors.role!=null)
          {
            Materialize.toast(error.response.data.errors.role[0],4000);
          }
          console.log(error.response.data.errors);
        });
      }
    },
  }
</script>
