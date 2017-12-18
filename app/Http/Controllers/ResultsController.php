<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\JudgesSetup;
use DB;
use App\Setup;
class ResultsController extends Controller
{
    public function show($setupId)
    {
      $judges=JudgesSetup::where('setup_id', $setupId)->get(['user_id']);
      $TotalContestant=Setup::where('id',$setupId)->get(['NumberContestant']);
      $preAvg = array();
      foreach ($judges as $key => $judge)
      {
        $preAvg[] = DB::table('ratings')->where('user_id', $judge->user_id)->where('setup_id',$setupId)
        ->select(DB::raw('avg(rate) as avg, contestant_no as Contestant'))
        ->groupBy('contestant_no')
        ->get();
      }
      // return $preAvg;
        $TotalAvg = DB::table('ratings')->where('setup_id',$setupId)
        ->select(DB::raw('avg(rate) as avg, contestant_no as Contestant'))
        ->groupBy('contestant_no')->orderBy('Contestant')
        ->get();
        $ranking = DB::table('ratings')->where('setup_id',$setupId)
        ->select(DB::raw('avg(rate) as avg, contestant_no as Contestant'))
        ->groupBy('contestant_no')->orderBy('avg','DESC')
        ->get();
        return view('Results.show',compact('judges','preAvg','TotalAvg','ranking','TotalContestant'));
    }
}
