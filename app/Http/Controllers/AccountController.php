<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\JudgesSetup;
use App\Setup;
class AccountController extends Controller
{
    public function __construct()
    {
      $this->middleware('AdminOnly');
    }
    public function index()
    {
      return view('Account.AccountManagement');
    }
    public function indexData()
    {
      return User::orderBy('id','DESC')->paginate(10);
    }
    public function delete($id)
    {
      $usertodelete=User::find($id);
      if($usertodelete->role==0)
      {
        $countadmin=User::where('role','0')->count();
        if ($countadmin==1)
        {
          return ['error'=>'This app requires 1 administrator'];
        }
      }
      $setups=JudgesSetup::where('user_id',$id)->get(['setup_id']);
      foreach ($setups as $setup)
      {
        Setup::where('id',$setup->setup_id)->delete();
      }
      User::where('id', $id)->delete();
    }
    public function fetchuser($id)
    {
      return $userData=User::where('id', $id)->get(['name','username','role','disabled','id']);
    }
    public function update(Request $request,$id)
    {
      if ($request->password!=null)
      {
        $this->validate($request,[
          'name'=>'required|max:30',
          'username'=>'max:20|required',
          'password'=>'confirmed'
        ]);
      }
      if ($request->password==null)
      {
        $this->validate($request,[
          'name'=>'required|max:30',
          'username'=>'max:20|required',
        ]);
      }

      $errorNameIfNotNull=User::where('name',$request->name)->where('id','!=',$id)->take(1)->get();
      if (isset($errorNameIfNotNull[0]))
      {
        return ['error'=>'Sorry another account is using this name'];
      }
      $errorUsernameifNotNull=User::where('username',$request->username)->where('id','!=',$id)->take(1)->get();
      if (isset($errorUsernameifNotNull[0]))
      {
        return ['error'=>'Sorry another account is using this username'];
      }

      if ($request->password!=null)
      {
        User::where('id', $id)->update(['name'=>$request->name,'username'=>$request->username,'password'=>bcrypt($request->password)]);
      }
      if($request->password==null)
      {
        User::where('id', $id)->update(['name'=>$request->name,'username'=>$request->username]);
      }
      return ['success'=>'success'];
    }
    public function setAdminRole($id)
    {
      User::where('id', $id)->update(['role'=>'0']);
      $name=User::where('id', $id)->value('name');
      return ['name'=>$name];
    }
    public function setJudgeRole($id)
    {
      $admincount=User::where('role', '0')->count();
      if ($admincount<=1)
      {
        return['error'=>'The app needs atleast 1 administrator '];
      }
      User::where('id', $id)->update(['role'=>'1']);
      $name=User::where('id', $id)->value('name');
      return ['name'=>$name];
    }
    public function toggleEnable($id)
    {
      $user=User::where('id', $id)->get(['disabled','role']);
      if ($user[0]->disabled==null)
      {
        $adminEnabledCount=User::where('role','0')->whereNull('disabled')->count();
        if ($adminEnabledCount<=1 && $user[0]->role=='0')
        {
          return ['error'=>'The app needs atleast 1 enabled administrator account'];
        }
        User::where('id', $id)->update(['disabled'=>'0']);
      }elseif($user[0]->disabled==0)
      {
        User::where('id', $id)->update(['disabled'=>NULL]);
      }
      return ['success'=>'success'];
    }
}
