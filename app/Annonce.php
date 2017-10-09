<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $table = 'annonce';

    public function code()
    {
        return $this->hasMany('App\Code');
    }
}
