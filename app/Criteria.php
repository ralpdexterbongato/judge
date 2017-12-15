<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
  public function Event()
  {
    return $this->belongsToMany('App\Event','criteria_events');
  }
}
