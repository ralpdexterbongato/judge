<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\CriteriaEvent;
class EventController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('AdminOnly');
    }
    public function index()
    {
      return view('Event.Index');
    }
    public function indexData()
    {
      return $events=Event::orderBy('id','DESC')->with('Criteria')->paginate(10);
    }
    public function create()
    {
      return view('Event.Create');
    }
    public function store(Request $request)
    {
      $this->validate($request,[
        'Title'=>'required',
        'Criterias'=>'required',
      ]);
      $eventTbl= new Event;
      $eventTbl->title=$request->Title;
      $eventTbl->save();

      $forEventCriteria = array();
      foreach ($request->Criterias as $criteria)
      {
        $forEventCriteria[] = array('event_id'=>$eventTbl->id,'criteria_id' =>$criteria );
      }
      CriteriaEvent::insert($forEventCriteria);
      return ['success'=>'success'];
    }
    public function delete($id)
    {
      Event::where('id', $id)->delete();
    }
}
