<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';

    public function credit()
    {
        return $this->hasMany('App\Credit','client_id');
    }
}
