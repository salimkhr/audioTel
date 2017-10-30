<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class TchatG extends Model
{
    protected $table = 'tchatG';
    use SoftDeletes;

    public function hotesse()
    {
        return $this->belongsTo('App\Hotesse','id_hotesse');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin','id_admin');
    }

    public function getClass()
    {
        if($this->id_hotesse !=null)
            return (Auth::user() instanceof Hotesse && $this->id_hotesse == Auth::id())?"in":"";
        else
            return (Auth::user() instanceof Admin && $this->id_admin == Auth::id())?"in":"";
    }

    public function getAuteur()
    {
        if($this->id_hotesse !=null)
            return $this->hotesse;
        else
            return $this->admin;
    }

    public function reads()
    {
        return $this->hasMany("TchatG","message_id");
    }
}