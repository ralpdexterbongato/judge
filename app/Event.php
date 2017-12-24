<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Event extends Model
{
    protected $table="events";
    public function Criteria()
    {
      return $this->belongsToMany('App\Criteria','criteria_events');
    }
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }
}
