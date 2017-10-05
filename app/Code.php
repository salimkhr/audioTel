<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'code';
    protected $primaryKey='code';

    public function hotesse()
    {
        return $this->belongsTo('App\Hotesse');
    }

    public function photo()
    {
        return $this->hasMany('App\PhotoCode','code');
    }

    public function annonce()
    {
        return $this->belongsTo('App\Annonce');
    }
}
