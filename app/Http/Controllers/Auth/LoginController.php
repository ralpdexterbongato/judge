<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class LoginController extends Controller
{
  public function Login(Request $request)
  {
    $this->validate($request,[
      'username'=>'required|max:20',
      'password'=>'required|max:20'
    ]);
    $credentials = array('username' => $request->username,'password'=>$request->password);
    if (Auth::attempt($credentials))
    {
      return redirect('/events-panel');
    }
    return redirect('/')->with('error', 'Incorrect Username or Password');
  }
  public function Logout()
  {
    Auth::logout();
    return redirect('/');
  }
}
