<?php

namespace App\Http\Controllers\Criteria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Criteria;
use App\CriteriaEvent;
class CriteriaController extends Controller
{
    public function __construct()
    {
      $this->middleware('AdminOnly');
      $this->middleware('auth');
    }
    public function store(Request $request)
    {
      if ($request->Name==null)
      {
        return ['error'=>'New Criteria cannot be empty'];
      }
      $criteriaTbl = new Criteria;
      $criteriaTbl->name = $request->Name;
      $criteriaTbl->save();
    }
    public function index()
    {
      return Criteria::all();
    }
    public function delete($id)
    {
      $notAllowedIfNotNull=CriteriaEvent::where('criteria_id', $id)->take(1)->get();
      if (!empty($notAllowedIfNotNull[0]))
      {
        return ['error'=>'Other events is still using this criteria'];
      }
      Criteria::where('id', $id)->delete();
      return ['success'=>'success'];
    }
}
