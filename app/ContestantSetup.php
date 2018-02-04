<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class ContestantSetup extends Model
{
    protected $table = 'contestant_setups';
    public $timestamps=false;
    public function Ratings()
    {
      return $this->hasMany('App\Rating','contestant_id')->where('user_id', Auth::user()->id);
    }
}
