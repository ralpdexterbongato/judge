<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
class LoginController extends Controller
{
  public function Login(Request $request)
  {
    $this->validate($request,[
      'username'=>'required|max:25',
      'password'=>'required|max:25'
    ]);
    $checkifdisabled=User::where('username', $request->username)->get(['disabled']);
    if ($checkifdisabled[0]->disabled == '0')
    {
      return redirect()->back()->with('error', 'Your account has been disabled by the administrator');
    }
    $credentials = array('username' => $request->username,'password'=>$request->password);
    if (Auth::attempt($credentials))
    {
      if (Auth::user()->role ==1)
      {
        return redirect('/rating-create');
      }else
      {
        return redirect('/events-panel');
      }
    }
    return redirect('/')->with('error', 'Incorrect Username or Password');
  }
  public function Logout()
  {
    Auth::logout();
    return redirect('/');
  }
}
