<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $table = 'client';
    protected $dates = ['deleted_at'];

    public function credit()
    {
        return $this->hasMany('App\Credit','client_id');
    }

    public function appel()
    {
        return $this->hasMany('App\Appel','client_id');
    }
}
