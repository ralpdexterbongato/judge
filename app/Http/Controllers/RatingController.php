<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Setup;
use Auth;
use App\Event;
use App\ContestantSetup;
use App\CriteriaSetup;
use DB;
class RatingController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function create()
    {
      return view('Rating.Create');
    }

    public function createData()
    {
     $response=[];
     $activitydata=Auth::user()->Setup(Auth::user()->id)->get();
     if (isset($activitydata[0]))
     {
       $eventAndCriteria=Event::find($activitydata[0]->event_id);

       $checkifHasContestant = ContestantSetup::where('setup_id', $activitydata[0]->id)->count();
       if ($checkifHasContestant==0)
       {
         return ['error'=>'The admin forgot to add contestants in this activity'];
       }
       $SetupEnabled = Setup::find($activitydata[0]->id);
       $criterias= $SetupEnabled->criterias()->get();
       $event = $SetupEnabled->Event()->get();
       $response = array('activity' =>$SetupEnabled,'event'=>$event,'eventCriteria'=>$criterias);
     }
     return response()->json($response);
    }
    public function storeRate(Request $request)
    {
      if ($request->Absent=='')
      {
        if ($request->Rates==[])
        {
          return ['error'=>'Please fill all scores'];
        }
        foreach ($request->Rates as $req)
        {
          if ($req==null||$req=='')
          {
            return ['error'=>'Please fill all scores'];
          }
        }
        foreach ($request->Crit as $key => $criteria)
        {
          $criteria=(object)$criteria;
          $RatingTbl = new Rating;
          $RatingTbl->user_id=Auth::user()->id;
          $RatingTbl->setup_id = $request->setUp;
          $RatingTbl->contestant_id = $request->Contestant;
          $RatingTbl->criteria_id = $criteria->id;
          $RatingTbl->rate = $request->Rates[$key];
          $RatingTbl->save();
        }
      }else
      {
        foreach ($request->Crit as $key => $criteria)
        {
          $criteria=(object)$criteria;
          $RatingTbl = new Rating;
          $RatingTbl->user_id=Auth::user()->id;
          $RatingTbl->setup_id = $request->setUp;
          $RatingTbl->contestant_id = $request->Contestant;
          $RatingTbl->criteria_id = $criteria->id;
          $RatingTbl->rate = '0';
          $RatingTbl->save();
        }
      }
      return['success'=>'success'];
    }
    public function Contestant($setupId)
    {

        return ContestantSetup::where('setup_id',$setupId)->with(array('Ratings'=>function($query) use ($setupId){
           $query->select()->where('setup_id',$setupId);
          }))->paginate(1);
    }
    public function updateRate(Request $request,$setupId)
    {
      if ($request->UpdateRates==[])
      {
        return ['error'=>'Please fill all scores'];
      }
      foreach ($request->UpdateRates as $req)
      {
        if ($req==null)
        {
          return ['error'=>'Please fill all scores'];
        }
      }
      foreach ($request->Criterias as $key => $criteria)
      {
        $criteria = (object)$criteria;
        Rating::where('setup_id',$setupId)->where('contestant_id', $request->Contestant)->where('user_id',Auth::user()->id)->where('criteria_id', $criteria->id)->update(['rate'=>$request->UpdateRates[$key]]);
      }
      return['success'=>'success'];
    }
    public function getOwnScoreRanking($setupid)
    {
      $totals = DB::table('ratings')
                 ->select(DB::raw('SUM(rate) as ratingtotal,contestant_id as contestant'))
                 ->where('setup_id', $setupid)
                 ->where('user_id', Auth::user()->id)
                 ->groupBy('contestant_id')
                 ->orderBy('ratingtotal','DESC')
                 ->get();
        $rankarray = array();
        $place=1;
        foreach ($totals as $key => $total)
        {
          if (((isset($totals[$key-1]))&&($totals[$key-1]->ratingtotal ==$total->ratingtotal)))
          {
            //do nothing
          }elseif ($key!=0)
          {
            $place = $place+1;
          }
          $name = ContestantSetup::where('id', $total->contestant)->value('name');
          $rankarray[] = array('name' =>$name,'totalrate'=>$total->ratingtotal,'place'=>$place);

        }
        return response()->json($rankarray);
    }
    public function checkifDone($setupid)
    {
      $numberofsubmited=Rating::where('setup_id', $setupid)->where('user_id', Auth::user()->id)->count();
      $numberofcontestants=ContestantSetup::where('setup_id', $setupid)->count();
      $numberofCriteria=CriteriaSetup::where('setup_id', $setupid)->count();
      $ans = $numberofcontestants * $numberofCriteria;
      if ($ans == $numberofsubmited)
      {
        return ['response'=>'0'];
      }else {
        return ['response'=>'1'];
      }
    }

}
