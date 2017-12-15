@extends('layouts.app')
@section('title')
Admin
@endsection
@section('content')
<div class="previous-events-container">
  <div class="card z-depth-2">
    <div class="card-content white blue-text">
        <h4 class="card-stats-number">NEW</h4>
        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i>
        </p>
    </div>
    <div class="card-action light-grey darken-2 bottom-btn-more">
        <div class="blue-text">Let's create!</div>
        <a href="{{route('create.event')}}" class="btn btn-floating pulse"><i class="material-icons">add</i></a>
    </div>
  </div>
  @foreach ($events as $event)
  <div class="card">
    <div class="card-content blue white-text">
        <h5 class="card-stats-number">{{$event->title}}</h5>
        @foreach ($event->criteria as $criteria)
          <div class="chip">
            {{$criteria->name}}
          </div>
        @endforeach
    </div>
    <div class="card-action blue darken-2 bottom-btn-more">
        <div class="white-text">Created {{$event->created_at->diffForHumans()}}</div>
        <a class="btn btn-floating" href="/setup-create/{{$event->id}}"><i class="material-icons">remove_red_eye</i></a>
    </div>
  </div>
  @endforeach
</div>
@endsection
