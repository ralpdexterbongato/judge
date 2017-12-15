<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Setup;
use App\JudgesSetup;
class SetupController extends Controller
{

  public function create($eventId)
  {
    $eventdata=Event::with('Criteria')->find($eventId);
    $judges=User::where('role', '1')->get();
    $EventId = array('id' => $eventId);
    $EventId = (object)$EventId;
    return view('Setup.Create',compact('eventdata','judges','EventId'));
  }
  public function store(Request $request)
  {
    $setup= new Setup;
    $setup->event_id=$request->eventid;
    $setup->Name=$request->NameActivity;
    $setup->NumberContestant=$request->ContestantNo;
    $setup->save();

    $forjoin = array();
    foreach ($request->JudgeSelected as $judge)
    {
      $forjoin[] = array('user_id' =>$judge,'setup_id'=>$setup->id);
    }
    JudgesSetup::insert($forjoin);
    return redirect()->back();
  }
}
