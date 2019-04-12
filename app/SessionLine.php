<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionLine extends Model
{
    protected $fillable = ['sid','line'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
