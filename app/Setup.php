<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Setup extends Model
{
    public function Judges()
    {
      return $this->belongsToMany('App\User','judges_setups');
    }
    public function Event()
    {
      return $this->belongsTo('App\Event');
    }

    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }
}
