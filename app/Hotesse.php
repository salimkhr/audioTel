<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Hotesse extends User
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'hotesse';
    protected $dates = ['deleted_at'];

    public function code()
    {
        return $this->hasMany('App\Code');
    }

    public function photos()
    {
        return $this->hasMany('App\PhotoHotesse');
    }

    public function photo()
    {
        return $this->belongsTo('App\PhotoHotesse','photoHotesse_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }

    public function annonces()
    {
        return $this->hasMany('App\Annonce');
    }
}
