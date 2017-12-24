<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Setup;
use App\JudgesSetup;
class SetupController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('AdminOnly');
  }
  public function create($eventId)
  {
    $eventdata=Event::with('Criteria')->find($eventId);
    $judges=User::where('role', '1')->whereNull('disabled')->get();
    $EventId = array('id' => $eventId);
    $EventId = (object)$EventId;
    return view('Setup.Create',compact('eventdata','judges','EventId'));
  }
  public function store(Request $request)
  {
    $this->validate($request,[
      'eventid'=>'required',
      'NameActivity'=>'required|max:30',
      'ContestantNo'=>'required'
    ]);
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
    return redirect()->back()->with('success', 'success');
  }
  public function index()
  {
    return view('Setup.index');
  }
  public function indexData()
  {
    return Setup::with('Judges','Event')->orderBy('id','DESC')->paginate(10);
  }
  public function delete($id)
  {
    Setup::where('id',$id)->delete();
    return ['success'=>'success'];
  }
  public function enable($id)
  {
    Setup::where('isActive', '0')->update(['isActive'=>null]);
    Setup::where('id', $id)->update(['isActive'=>'0']);
  }
  public function disable($id)
  {
    Setup::where('id', $id)->update(['isActive'=>null]);
  }
}
