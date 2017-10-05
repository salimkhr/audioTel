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

    public function photos()
    {
        return $this->hasMany('App\PhotoHotesse');
    }

    public function annonces()
    {
        return $this->hasMany('App\Annonce');
    }

    public function photo()
    {
        return $this->belongsTo('App\PhotoHotesse','photoHotesse_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }
}
