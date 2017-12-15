<?php

namespace App\Http\Controllers\Criteria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Criteria;
class CriteriaController extends Controller
{
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
}
