@extends('layouts.app')
@section('title')
  Welcome
@endsection
@section('content')
  <div class="login-form-container">
    <div class="login-form white z-depth-1">
      <form action="{{route('loginTry')}}" method="post">
        {{ csrf_field() }}
        <div class="input-field col s6">
          <input id="username" name="username" type="text" class="{{$errors->has('username')?'invalid':''}}">
          <label for="username" data-error="{{$errors->first('username')}}">Username</label>
        </div>
        <div class="input-field col s6">
          <input id="password" name="password"  type="password" class="{{$errors->has('password')?'invalid':''}}">
          <label for="password" data-error="{{$errors->first('password')}}">Password</label>
        </div>
        <div class="login-btn-container">
          <button class="btn pulse waves-effect waves-light blue" type="submit" name="action">Login
            <i class="material-icons right">send</i>
          </button>
        </div>
      </form>

    </div>
  </div>
@endsection
