<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hotesse extends Authenticatable
{
    use Notifiable;
    protected $table = 'hotesse';

    public function code()
    {
        return $this->hasMany('App\Code');
    }
}
