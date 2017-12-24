<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Setup;
use Auth;
use App\Event;
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
     $activitydata=Auth::user()->Setup(Auth::user()->id)->get();
     $response=[];
     if (isset($activitydata[0]))
     {
       $eventAndCriteria=Event::with('Criteria')->find($activitydata[0]->event_id);
       $response = array('activity' =>$activitydata,'eventCriteria'=>$eventAndCriteria);
     }
     return response()->json($response);
    }
    public function store(Request $request)
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
          if ($req<75)
          {
            return ['error'=>'Score must be atleast 75'];
          }
          if ($req>100)
          {
            return ['error'=>'Score maximum is 100'];
          }
        }
        foreach ($request->Crit as $key => $criteria)
        {
          $criteria=(object)$criteria;
          $RatingTbl = new Rating;
          $RatingTbl->user_id=Auth::user()->id;
          $RatingTbl->setup_id = $request->setUp;
          $RatingTbl->contestant_no = $request->Contestant;
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
          $RatingTbl->contestant_no = $request->Contestant;
          $RatingTbl->criteria_id = $criteria->id;
          $RatingTbl->rate = '75';
          $RatingTbl->save();
        }
      }
    }
    public function Contestant($setupId,$contestant)
    {
      $ratings= Rating::where('setup_id', $setupId)->where('contestant_no',$contestant)->where('user_id',Auth::user()->id)->get();
      if (empty($ratings[0]))
      {
        $response = array('ratings' =>null,'average'=>null);
        return response()->json($response);
      }else
      {
        $total=0;
        foreach ($ratings as $key => $rating)
        {
          $total= $total + $rating->rate;
        }
        $criteriacount=$key+1;
        $average=$total/$criteriacount;
        $average=number_format($average, 2, '.', ',');
        $response = array('ratings' =>$ratings,'average'=>$average);
        return response()->json($response);
      }

    }
    public function update(Request $request,$setupId)
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
        }elseif ($req<75)
        {
          return ['error'=>'Score must be atleast 75'];
        }elseif ($req>100)
        {
          return ['error'=>'Score maximum is 100'];
        }
      }
      foreach ($request->Criterias as $key => $criteria)
      {
        Rating::where('setup_id',$setupId)->where('contestant_no', $request->Contestant)->where('criteria_id', $criteria)->update(['rate'=>$request->UpdateRates[$key]]);
      }
    }
}
