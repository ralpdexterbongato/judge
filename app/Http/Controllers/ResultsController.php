<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\JudgesSetup;
use DB;
use App\Setup;
use Session;
class ResultsController extends Controller
{
    public function __construct()
    {
      $this->middleware('AdminOnly');
      $this->middleware('auth');
    }
    public function show($setupId)
    {
      Session::forget('SessionRank');
      $judges=JudgesSetup::where('setup_id', $setupId)->get(['user_id']);
      $SetupData=Setup::with('Event')->where('id',$setupId)->get();
      $preAvg = array();
      foreach ($judges as $key => $judge)
      {
        $preAvg[] = DB::table('ratings')->where('user_id', $judge->user_id)->where('setup_id',$setupId)
        ->select(DB::raw('avg(rate) as avg, contestant_no as Contestant'))
        ->groupBy('contestant_no')
        ->get();
      }
        $TotalAvg = DB::table('ratings')->where('setup_id',$setupId)
        ->select(DB::raw('avg(rate) as avg, contestant_no as Contestant'))
        ->groupBy('contestant_no')->orderBy('Contestant')
        ->get();
        $ranking = DB::table('ratings')->where('setup_id',$setupId)
        ->select(DB::raw('avg(rate) as avg, contestant_no as Contestant'))
        ->groupBy('contestant_no')->orderBy('avg','DESC')
        ->get();

        $place=1;
        foreach ($ranking as $key => $rank)
        {
          if (((isset($ranking[$key-1]))&&($ranking[$key-1]->avg == $rank->avg)))
          {
            //do nothing
          }elseif ($key!=0)
          {
            $place = $place+1;
          }
          $rankarray =['place'=>$place,'contestant'=>$rank->Contestant,'avg'=>$rank->avg];
          $rankarray = (object)$rankarray;
          Session::push('SessionRank',$rankarray);
        }

        return view('Results.show',compact('judges','preAvg','TotalAvg','SetupData'));
    }
}
