<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="icon.png">
    <link rel="stylesheet" href="/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Pacifico" rel="stylesheet">
    <link rel="stylesheet" href="/css/mystyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.1.0/sweetalert2.css">
    <script type="text/javascript" src="/js/jquery.js">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.1.0/sweetalert2.min.js">
    </script>
    <title>@yield('title')</title>
  </head>
  <body>
    <header>
      @Auth
      <ul id="slide-out" class="side-nav">
        <li>
          <div class="user-view">
            <div class="background">
              <img src="/img/bg.jpg">
            </div>
            <a href="#!user"><img></a>
            <a href="#!name"><span class="white-text name">{{Auth::user()->name}}</span></a>
            <a href="#!email"><span class="white-text email">{{Auth::user()->username}}</span></a>
          </div>
        </li>
        @if (Auth::check() && Auth::user()->role==0)
        <li><a class="waves-effect" href="/events-panel"><i class="material-icons">cloud</i>Events</a></li>
        <li><a class="waves-effect" href="/setup-index"><i class="material-icons">cloud</i>Activities</a></li>
        @endif
        <li><a class="waves-effect" href="/rating-create"><i class="material-icons">cloud</i>Judging</a></li>
        @if (Auth::check() && Auth::user()->role==0)
        <li><div class="divider"></div></li>
        <li><a class="waves-effect" href="/register"><i class="material-icons">add</i>Account</a></li>
        <li><a class="waves-effect" href="/account-index"><i class="material-icons">people</i>Manage accounts</a></li>
        @endif
        <li><div class="divider"></div></li>
        <li><a class="waves-effect" href="#!" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">exit_to_app</i>Logout</a></li>
      </ul>
      <ul id="dropdown1" class="dropdown-content">
        <li class="divider"></li>
        @if (Auth::user()->role==0)
        <li><a href="/register">Create account</a></li>
        <li><a href="/account-index">Manage accounts</a></li>
        @endif
        <li><a href="#!"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
        <form style="display:none" id="logout-form" action="{{route('logout')}}" method="post">
          {{ csrf_field() }}
        </form>
      </ul>
      @endAuth
      <nav class="blue darken-1">
        @if (Auth::check())
          <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
        @endif
        <div class="nav-wrapper">
          <a href="/" class="brand-logo">Judging App</a>
          <ul class="right hide-on-med-and-down">
            @if (Auth::check() && Auth::user()->role==0)
              <li><a href="/events-panel">Events</a></li>
              <li><a href="/setup-index">Activities</a></li>
            @endif
            @Auth
              <li><a href="/rating-create">Judging</a></li>
            @endAuth
            <!-- Dropdown Trigger -->
            <li>
              <a class="dropdown-button" href="#!" data-activates="dropdown1">
                @Auth
                {{Auth::user()->name}}
                <i class="material-icons right">arrow_drop_down</i>
                @endAuth
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <div class="content-container" id="app">
      @section('content')
      @show
    </div>
    <footer class="page-footer blue darken-1">
         <div class="container">
           <div class="row">
             <div class="col l6 s12">
               <h5 class="white-text">Capstone project</h5>
               <p class="grey-text text-lighten-4"></p>
             </div>
             <div class="col l4 offset-l2 s12">
               <h5 class="white-text">Group members</h5>
               <ul>
                 <li><a class="grey-text text-lighten-3" href="#!">Joronie Roluna</a></li>
                 <li><a class="grey-text text-lighten-3" href="#!">Eunice Fe Claros</a></li>
                 <li><a class="grey-text text-lighten-3" href="#!">Jacky Tirambolo</a></li>
                 <li><a class="grey-text text-lighten-3" href="#!">Baw kinsa</a></li>
               </ul>
             </div>
           </div>
         </div>
         <div class="footer-copyright">
           <div class="container">
           © 2017 Copyright, All rights reserved - Mater Dei College
           </div>
         </div>
       </footer>
       @if (Session::get('success')!=null)
         <script type="text/javascript">
         swal(
             'Success!',
             'Thank you',
             'success'
           )
         </script>
       @endif
       @if ($errors->all()!=null)
         <script type="text/javascript">
         swal(
             'Oops! something is not right',
             'Please fillup again',
             'error'
           )
         </script>
       @endif
       @if (Session::has('error'))
         <script type="text/javascript">
         swal(
             'Oops! something is not right',
             '{{Session::get('error')}}',
             'error'
           )
         </script>
       @endif

    <script type="text/javascript">
      $(document).ready(function() {
        $(".button-collapse").sideNav();
        $('select').material_select();
         $(".button-collapse").sideNav();
         $('.modal').modal();

      });
    </script>
    <script type="text/javascript" src="/js/app.js">
    </script>
    <script type="text/javascript" src="/js/materialize.min.js">
    </script>
  </body>
</html>
