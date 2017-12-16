<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
