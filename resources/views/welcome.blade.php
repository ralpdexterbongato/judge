@extends('layouts.app')
@section('title')
  Welcome
@endsection
@section('content')
  <div class="login-form-container">
    <div class="login-form white z-depth-5">
      @if (Session::has('error'))
        <p class="form-error red-text">{{Session::get('error')}}</p>
      @endif
      <form action="{{route('loginTry')}}" method="post">
        {{ csrf_field() }}
        <div class="input-field col s6">
          <i class="material-icons prefix">account_circle</i>
          <input id="username" name="username" type="text" class="validate">
          <label for="username">Username</label>
          @if ($errors->has('username'))
            <p class="red-text form-error">{{$errors->first('username')}}</p>
          @endif
        </div>
        <div class="input-field col s6">
          <i class="material-icons prefix">vpn_key</i>
          <input id="password" name="password" type="password" class="validate">
          <label for="password">Password</label>
          @if ($errors->has('password'))
            <p class="red-text form-error">{{$errors->first('password')}}</p>
          @endif
        </div>
        <button class="btn pulse waves-effect waves-light right blue" type="submit" name="action">Login
          <i class="material-icons right">send</i>
        </button>
      </form>

    </div>
  </div>
@endsection
