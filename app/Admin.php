<?php

namespace App;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
    use Notifiable;
    protected $table = 'admin';

    public function photos()
    {
        return $this->hasMany('App\PhotoAdmin');
    }

    public function photo()
    {
        return $this->belongsTo('App\PhotoAdmin','photoAdmin_id');
    }

    public function hotesses()
    {
        return $this->hasMany('App\Hotesse');
    }
}
