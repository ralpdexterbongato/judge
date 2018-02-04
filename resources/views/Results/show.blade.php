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
    <div class="ranking-table-container">
      <table class="striped responsive-table">
        <thead>
          <tr>
            <th>Ranking</th>
            <th>Contestant No.</th>
            <th>Average</th>
          </tr>
        </thead>
        <tbody>
          @foreach (Session::get('SessionRank') as $key => $displayrank)
          <tr>
            @if ($displayrank->place-1==0)
              <th><i class="material-icons">grade</i> Winner</th>
            @else
              <th>{{$displayrank->place-1}} runner up</th>
            @endif
            <td>{{$displayrank->contestant[0]->name}}</td>
            <td><span class="bold">{{number_format($displayrank->totalRate,2,'.',',')}}%</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="results-table-container">
      <table class="striped responsive-table">
        <thead>
          <tr>
            <th>Contestant</th>
            @foreach ($judges as $judge)
              <th>{{$judge->User->name}}</th>
            @endforeach
            <th>Avarage</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($allContestants as $i => $contestant)
          <tr>
            <td>{{$contestant->name}}</td>
            @foreach ($judges as $key =>$judge)
            <td>{{number_format($preAvg[$key][$i]->total, 2, '.', ',')}} %</td>
            @endforeach
              <td class="bold">{{number_format($TotalAvg[$i]->total/$numberOfJudges, 2, '.', ',')}} %</td>
            <td><span class="bold"></span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
      <h5 class="not-found grey-text">Results is still unavailable</h5>
    @endif
  </div>
@endsection
