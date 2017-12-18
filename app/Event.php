<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table="events";
    public function Criteria()
    {
      return $this->belongsToMany('App\Criteria','criteria_events');
    }
}
