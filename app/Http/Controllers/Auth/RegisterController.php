<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
class RegisterController extends Controller
{
    public function __construct()
    {
      $this->middleware('AdminOnly');
      $this->middleware('auth');
    }
    public function create()
    {
      return view('Auth.register');
    }
    public function store(Request $request)
    {
      $this->validate($request,[
        'name'=>'required|unique:users',
        'username'=>'required|unique:users',
        'password'=>'required|confirmed',
        'role'=>'required|max:1|numeric'
      ]);

      $userTbl= new User;
      $userTbl->name = $request->fullname;
      $userTbl->username = $request->username;
      $userTbl->password = bcrypt($request->password);
      $userTbl->role = $request->role;
      $userTbl->save();
      return redirect()->back()->with('success', 'success');
    }
}
