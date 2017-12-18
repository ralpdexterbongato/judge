<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Setup($id)
    {
      return $this->belongsToMany('App\Setup','judges_setups')->wherePivot('user_id',$id)->where('isActive', '0');
    }
    // public function Ratings($judgeId)
    // {
    //   return $this->hasMany('App\Rating')->where('user_id', $judgeId
    // }
}
