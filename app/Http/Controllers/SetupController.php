<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Setup;
use App\JudgesSetup;
use App\CriteriaSetup;
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
    $EventId = json_encode($EventId);
    return view('Setup.Create',compact('eventdata','judges','EventId'));
  }
  public function store(Request $request)
  {
    $this->validate($request,[
      'eventid'=>'required',
      'NameActivity'=>'required|max:30',
      'percent.*'=>'required|numeric|min:1|max:100',
    ]);

    if (count($request->percent)!=count($request->criterias))
    {
      return ['error'=>'Please fill all the criterias'];
    }

    $setup= new Setup;
    $setup->event_id=$request->eventid;
    $setup->Name=$request->NameActivity;
    $setup->save();

    $forjoin = array();
    foreach ($request->judges as $judge)
    {
      $forjoin[] = array('user_id' =>$judge,'setup_id'=>$setup->id);
    }
    $forCriteriaSetups = array();

    foreach ($request->criterias as $key => $criteria)
    {
      $criteria = (object)$criteria;
      $forCriteriaSetups[] = array('criteria_id' => $criteria->id,'setup_id' =>$setup->id ,'percentjudging'=>$request->percent[$key] );
    }
    CriteriaSetup::insert($forCriteriaSetups);
    JudgesSetup::insert($forjoin);
    return ['success'=>'success'];
  }
  public function index()
  {
    return view('Setup.index');
  }
  public function indexData()
  {
    return Setup::with('Judges','Event','Contestants')->orderBy('id','DESC')->paginate(9);
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
