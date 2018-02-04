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
    public function Contestants()
    {
      return $this->hasMany('App\ContestantSetup','setup_id');
    }
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }
    public function criterias()
    {
      return $this->belongsToMany('App\Criteria','criteria_setups','setup_id','criteria_id')->withPivot('percentjudging');
    }
}
