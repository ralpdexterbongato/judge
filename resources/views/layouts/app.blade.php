<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/css/mystyle.css">
    <title>@yield('title')</title>
  </head>
  <body>
    <header>
      <ul id="dropdown1" class="dropdown-content">
        <li><a href="#!">sample</a></li>
        <li><a href="#!">sample</a></li>
        <li><a href="#!">sample</a></li>
        <li class="divider"></li>
        @Auth
        <li><a href="/register">Account</a></li>
        <li><a href="#!"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
        <form style="display:none" id="logout-form" action="{{route('logout')}}" method="post">
          {{ csrf_field() }}
        </form>
        @else
        <li><a href="/">Login</a></li>
        @endAuth
      </ul>
      <nav class="blue">
        <div class="nav-wrapper">
          <a href="#!" class="brand-logo">J-APP</a>
          <ul class="right hide-on-med-and-down">
            <li><a href="/events-panel">Events</a></li>
            <li><a href="#">Activities</a></li>
            <!-- Dropdown Trigger -->
            <li>
              <a class="dropdown-button" href="#!" data-activates="dropdown1">
                @Auth
                {{Auth::user()->name}}
                <i class="material-icons right">arrow_drop_down</i>
                @else
                <i class="material-icons">person</i>
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
    <footer class="page-footer blue">
         <div class="container">
           <div class="row">
             <div class="col l6 s12">
               <h5 class="white-text">Footer Content</h5>
               <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
             </div>
             <div class="col l4 offset-l2 s12">
               <h5 class="white-text">Links</h5>
               <ul>
                 <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                 <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                 <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                 <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
               </ul>
             </div>
           </div>
         </div>
         <div class="footer-copyright">
           <div class="container">
           © 2014 Copyright Text
           <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
           </div>
         </div>
       </footer>
    <script type="text/javascript" src="/js/jquery.js">
    </script>
    <script type="text/javascript">
      $(document).ready(function() {
        $(".button-collapse").sideNav();
        $('select').material_select();
      });
    </script>
    <script type="text/javascript" src="/js/app.js">
    </script>
    <script type="text/javascript" src="/js/materialize.min.js">
    </script>
  </body>
</html>
