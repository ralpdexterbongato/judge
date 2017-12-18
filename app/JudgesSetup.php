<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JudgesSetup extends Model
{
    public function User()
    {
      return $this->belongsTo('App\User');
    }
}
