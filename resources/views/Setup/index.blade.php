@extends('layouts.app')
@section('title')
  Activities | setup
@endsection
@section('content')
  <div class="activities-setup-container">
    @foreach ($ActivityAndJudges as $activity)
    <div class="activities-box">
      <div class="card blue darken-1">
        <div class="card-content white-text">
          <span class="card-title">{{$activity->Name}}</span>
          <table>
            <tr>
              <th>Event:</th>
              <td>{{$activity->event->title}}</td>
            </tr>
            <tr>
              <th>Contestants:</th>
              <td>{{$activity->NumberContestant}}</td>
            </tr>
            <tr>
              <th>Judges:</th>
            </tr>
            <tr>
              @foreach ($activity->judges as $judge)
                <td>{{$judge->name}}</td>
              @endforeach
            </tr>
          </table>
        </div>
        <div class="card-action white setup-actions">
          <p>{{$activity->created_at->diffForHumans()}}</p>
          <span>
            <a href="#">Disabled</a>
            <a href="#" class="btn btn-floating"><i class="material-icons white-text">remove_red_eye</i></a>
            <a href="#" class="btn btn-floating"><i class="material-icons white-text">delete</i></a>
            <form action="/setup-delete/{{$activity->id}}" id="delete-setup" method="post" style="display:none">
              {{ csrf_field() }}
              {{method_field('delete')}}
            </form>
          </span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endsection
