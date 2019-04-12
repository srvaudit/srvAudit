<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    protected $dates = ['created_at', 'updated_at', 'start', 'end'];
    protected $fillable = ['sid','cwd','rval','no','pipes','cmd'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
