@extends('layouts.app')
@section('title')
  Results viewer
@endsection
@section('content')
  <div class="results-container">
    <h5>Activity: {{$SetupData[0]->Name}}</h5>
    <h6>Event: {{$SetupData[0]->event->title}}</h6>
    <h6>Total contestants: {{count($allContestants)}}</h6>
    @if (Session::has('SessionRank'))
    <results :numberofjudges="{{json_encode($numberOfJudges)}}" :totalavg="{{json_encode($TotalAvg)}}" :preavg="{{json_encode($preAvg)}}" :allcontestant="{{$allContestants}}" :judges="{{$judges}}" :sessionrank="{{json_encode(Session::get('SessionRank'))}}"></results>
    @else
      <h5 class="not-found grey-text">Results is still unavailable</h5>
    @endif
  </div>
@endsection
