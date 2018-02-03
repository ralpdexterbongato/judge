@extends('layouts.app')
@section('title')
  Setup contest
@endsection
@section('content')
<div class="Contest-Setup-Container">
  <createactivity :eventdata="{{$eventdata}}" :judges="{{$judges}}" :eventid="{{$EventId}}"></createactivity>
</div>
@endsection
