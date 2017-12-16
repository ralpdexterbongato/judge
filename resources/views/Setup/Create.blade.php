@extends('layouts.app')
@section('title')
  Setup contest
@endsection
@section('content')
<div class="Contest-Setup-Container">
  <div class="setup-form">
    <form action="/setup-store" method="post">
      {{ csrf_field() }}
      <div class="input-field col s6">
        <i class="material-icons prefix">local_activity</i>
        <input id="icon_prefix" name="NameActivity" placeholder="Name of activity" type="text" class="validate">
        <label for="icon_prefix">Activity</label>
      </div>
      <div class="input-field col s6">
        <i class="material-icons prefix">people</i>
        <input name="ContestantNo" min="2" id="contestant"placeholder="Number of contestants" type="number" class="validate">
        <label for="contestant">Contestants</label>
      </div>
      <div class="input-field col s12">
          <i class="material-icons prefix">title</i>
          <input disabled value="{{$eventdata->title}}" id="disabled" type="text" class="validate">
          <label for="disabled">Event type</label>
      </div>
      <input type="text" name="eventid" value="{{$EventId->id}}" style="display:none">
      <div class="input-field col s12">
        <i class="material-icons prefix">person</i>
        <select multiple name="JudgeSelected[]">
          <option value="" disabled selected>Choose your judges</option>
          @foreach ($judges as $judge)
            <option value="{{$judge->id}}">{{$judge->name}}</option>
          @endforeach
        </select>
        <label>Select from judges</label>
      </div>
      <p>Event criteria:</p>
      <p>
        @foreach ($eventdata->criteria as $criteria)
          <div class="chip">
            {{$criteria->name}}
          </div>
        @endforeach
      </p>
      <button class="btn waves-effect waves-light right" type="submit" name="action">
        Save
      </button>
    </form>
  </div>
</div>
@endsection
