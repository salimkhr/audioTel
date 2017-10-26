<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class AccesAPI extends Model
{
    protected $table = 'AccesAPI';

    public function api()
    {
        return $this->belongsTo('App\API','id_API');
    }
}
