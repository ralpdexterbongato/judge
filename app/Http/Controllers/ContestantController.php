<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContestantSetup;
use Carbon\Carbon;
use Image;
class ContestantController extends Controller
{
    public function store(Request $request, $setupId)
    {
      if ($request->image!=null)
      {
        $imageData = $request->get('image');
        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path('/storage/images/').$fileName);
      }else
      {
        $fileName = '';
      }

      $contestantDB = new ContestantSetup;
      $contestantDB->setup_id = $setupId;
      $contestantDB->name= $request->name;
      $contestantDB->picture = $fileName;
      $contestantDB->save();
    }
    public function delete($contestantid)
    {
      ContestantSetup::where('id', $contestantid)->delete();
    }
}
