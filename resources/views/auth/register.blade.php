@extends('layouts.app')
@section('title')
  Registration
@endsection
@section('content')
<div class="Create-Acc-container">
  <div class="create-acc-form">
    <form action="{{route('registerSave')}}" method="post">
      {{ csrf_field() }}
      <div class="input-field col s6">
        <i class="material-icons prefix">account_circle</i>
        <input id="icon_prefix" name="fullname" type="text" class="validate">
        <label for="icon_prefix">Full name</label>
        @if ($errors->has('fullname'))
          <p class="form-error red-text">{{$errors->first('fullname')}}</p>
        @endif
      </div>
      <div class="input-field col s6">
        <i class="material-icons prefix">verified_user</i>
        <input id="icon_username" name="username" type="text" class="validate">
        <label for="icon_username">Username</label>
        @if ($errors->has('username'))
          <p class="form-error red-text">{{$errors->first('username')}}</p>
        @endif
      </div>
      <div class="input-field col s6">
        <i class="material-icons prefix">vpn_key</i>
        <input id="icon_pass" name="password" type="password" class="validate">
        <label for="icon_pass">Password</label>
      </div>
      <div class="input-field col s6">
        <i class="material-icons prefix">vpn_key</i>
        <input id="icon_confirm" name="password_confirmation" type="password" class="validate">
        <label for="icon_confirm">Confirm password</label>
        @if ($errors->has('password'))
          <p class="form-error red-text">{{$errors->first('password')}}</p>
        @endif
      </div>
      <div class="row">
        <p>
        <input name="role" type="radio" value="0" id="role1" />
        <label for="role1">Admin</label>
        </p>
        <p>
          <input name="role" type="radio" value="1" id="role2" />
          <label for="role2">Judge</label>
        </p>
      </div>
      @if ($errors->has('role'))
        <p class="form-error red-text">{{$errors->first('role')}}</p>
      @endif
      <button class="btn waves-effect waves-light right" type="submit">Submit
       <i class="material-icons right">send</i>
      </button>
    </form>
  </div>
</div>
@endsection
