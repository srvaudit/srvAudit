<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['sid','hostname','username','ticket_url','notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commands()
    {
        return $this->hasMany(Command::class);
    }
}
