@extends('layouts.app')
@section('title')
  Results viewer
@endsection
@section('content')
  <div class="results-container">
    <h5>Activity: {{$SetupData[0]->Name}}</h5>
    <h6>Event: {{$SetupData[0]->event->title}}</h6>
    <h6>Total contestants: {{$SetupData[0]->NumberContestant}}</h6>
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
            <td>{{$displayrank->contestant}}</td>
            <td><span class="bold">{{number_format($displayrank->avg,2,'.',',')}}%</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="results-table-container">
      <table class="striped responsive-table">
        <thead>
          <tr>
            <th>Contestant No.</th>
            @foreach ($judges as $judge)
              <th>{{$judge->User->name}}</th>
            @endforeach
            <th>Avarage</th>
          </tr>
        </thead>
        <tbody>
          @for ($i = 0; $i < $SetupData[0]->NumberContestant; $i++)
          <tr>
            <td>{{$i+1}}</td>
            @foreach ($judges as $key =>$judge)
            <td>{{number_format($preAvg[$key][$i]->avg, 2, '.', ',')}} %</td>
            @endforeach
              <td class="bold">{{number_format($TotalAvg[$i]->avg, 2, '.', ',')}} %</td>
            <td><span class="bold"></span></td>
          </tr>
          @endfor
        </tbody>
      </table>
    </div>
    @else
      <h5 class="not-found grey-text">Results is still unavailable</h5>
    @endif
  </div>
@endsection
