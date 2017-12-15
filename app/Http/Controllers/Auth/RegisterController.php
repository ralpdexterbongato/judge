<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
class RegisterController extends Controller
{
    public function create()
    {
      return view('Auth.register');
    }
    public function store(Request $request)
    {
      $this->validate($request,[
        'fullname'=>'required',
        'username'=>'required',
        'password'=>'required|confirmed',
        'role'=>'required|max:1|numeric'
      ]);

      $userTbl= new User;
      $userTbl->name = $request->fullname;
      $userTbl->username = $request->username;
      $userTbl->password = bcrypt($request->password);
      $userTbl->role = $request->role;
      $userTbl->save();
      return redirect()->back();
    }
}
