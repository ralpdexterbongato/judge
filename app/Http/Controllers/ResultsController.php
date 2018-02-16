<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\JudgesSetup;
use DB;
use App\Setup;
use Session;
use App\ContestantSetup;
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
      $judges=JudgesSetup::where('setup_id', $setupId)->with('User')->get(['user_id','id']);
      $numberOfJudges = count($judges);
      $SetupData=Setup::with('Event')->where('id',$setupId)->get();
      $preAvg = array();
      foreach ($judges as $key => $judge)
      {
        $preAvg[] = DB::table('ratings')->where('user_id', $judge->user_id)->where('setup_id',$setupId)
        ->select(DB::raw('SUM(rate) as total, contestant_id as Contestant'))
        ->groupBy('contestant_id')
        ->get();
      }
        $TotalAvg = DB::table('ratings')->where('setup_id',$setupId)
        ->select(DB::raw('SUM(rate) as total, contestant_id as Contestant'))
        ->groupBy('contestant_id')->orderBy('Contestant')
        ->get();
        $ranking = DB::table('ratings')->where('setup_id',$setupId)
        ->select(DB::raw('SUM(rate) as total, contestant_id as Contestant'))
        ->groupBy('contestant_id')->orderBy('total','DESC')
        ->get();

        $place=1;
        foreach ($ranking as $key => $rank)
        {
          if (((isset($ranking[$key-1]))&&($ranking[$key-1]->total == $rank->total)))
          {
            //do nothing
          }elseif ($key!=0)
          {
            $place = $place+1;
          }
          $totalRating=$rank->total / $numberOfJudges;
          $contestantData=ContestantSetup::where('id', $rank->Contestant)->get(['name','picture']);
          $rankarray =['place'=>$place,'contestant'=>$contestantData,'totalRate'=>$totalRating];
          $rankarray = (object)$rankarray;
          Session::push('SessionRank',$rankarray);
        }
        $allContestants=ContestantSetup::where('setup_id', $setupId)->get();
        return view('Results.show',compact('judges','preAvg','TotalAvg','SetupData','numberOfJudges','allContestants'));
    }
}
