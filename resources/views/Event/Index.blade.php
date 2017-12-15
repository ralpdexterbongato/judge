@extends('layouts.app')
@section('title')
Admin
@endsection
@section('content')
<div class="previous-events-container">
  <div class="card z-depth-2">
    <div class="card-content white blue-text">
        <p class="card-stats-title"><i class="material-icons">whatshot</i> Winner:???</p>
        <h4 class="card-stats-number">NEW</h4>
        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> 0% average rating
        </p>
    </div>
    <div class="card-action light-grey darken-2 bottom-btn-more">
        <div class="blue-text">Let's create!</div>
        <a href="{{route('create.event')}}" class="btn btn-floating pulse"><i class="material-icons">add</i></a>
    </div>
  </div>
  <div class="card">
    <div class="card-content blue white-text">
        <p class="card-stats-title"><i class="material-icons">whatshot</i> Winner: No. 5</p>
        <h4 class="card-stats-number">Singing</h4>
        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> 99.4% average rating
        </p>
    </div>
    <div class="card-action blue darken-2 bottom-btn-more">
        <div class="white-text">Created 5 hours ago</div>
        <a class="btn btn-floating"><i class="material-icons">remove_red_eye</i></a>
    </div>
  </div>
  <div class="card">
    <div class="card-content blue white-text">
        <p class="card-stats-title"><i class="material-icons">whatshot</i> Winner: No.4</p>
        <h4 class="card-stats-number">Dancing</h4>
        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> 99.4% average rating
        </p>
    </div>
    <div class="card-action blue darken-2 bottom-btn-more">
        <div class="white-text">Created 5 hours ago</div>
        <a class="btn btn-floating"><i class="material-icons">remove_red_eye</i></a>
    </div>
  </div>
  <div class="card">
    <div class="card-content blue white-text">
        <p class="card-stats-title"><i class="material-icons">whatshot</i> Winner: No.3</p>
        <h4 class="card-stats-number">Drama</h4>
        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> 99.4% average rating
        </p>
    </div>
    <div class="card-action blue darken-2 bottom-btn-more">
        <div class="white-text">Created 5 hours ago</div>
        <a class="btn btn-floating"><i class="material-icons">remove_red_eye</i></a>
    </div>
  </div>
</div>
@endsection
