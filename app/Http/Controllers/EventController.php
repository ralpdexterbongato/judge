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
    }
    public function index()
    {
      $events=Event::orderBy('id','DESC')->with('Criteria')->paginate(5);
      return view('Event.Index',compact('events'));
    }
    public function create()
    {
      return view('Event.Create');
    }
    public function store(Request $request)
    {
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
}
