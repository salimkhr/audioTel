<?php

namespace App;

use Illuminate\Notifications\Notifiable;

class Hotesse extends User
{
    use Notifiable;
    protected $table = 'hotesse';

    public function code()
    {
        return $this->hasMany('App\Code');
    }

    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }
}
