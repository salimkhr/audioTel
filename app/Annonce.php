<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annonce extends Model
{
    protected $table = 'annonce';
    use SoftDeletes;

    public function code()
    {
        return $this->hasMany('App\Code');
    }
}
