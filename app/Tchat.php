<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tchat extends Model
{
    protected $table = 'tchat';

    public function hotesse()
    {
        return $this->belongsTo('App\Hotesse','hotesse_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin','admin_id');
    }

    public function getClass()
    {
        if(Auth::user() instanceof Admin)
            return ($this->expediteur == "A")?"in":"";
       else
            return ($this->expediteur == "H")?"in":"";
    }

    public function getAuteur()
    {
        if($this->expediteur == "A")
            return $this->admin;
        else
            return $this->hotesse;
    }

}