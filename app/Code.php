<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
    use SoftDeletes;

    protected $table = 'code';
    protected $primaryKey='code';
    protected $dates = ['deleted_at'];

    public function hotesse()
    {
        return $this->belongsTo('App\Hotesse');
    }

    public function photo()
    {
        return $this->hasMany('App\PhotoHotesse','code');
    }

    public function appel()
    {
        return $this->hasMany('App\Appel','code');
    }

    public function getPhoto()
    {
        return $this->belongsTo('App\PhotoHotesse',"photoHotesse_id");
    }

    public function annonce()
    {
        return $this->belongsTo('App\Annonce');
    }

    public function annonces()
    {
        return $this->hasMany('App\Annonce','code');
    }
}
