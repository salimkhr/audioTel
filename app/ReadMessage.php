<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReadMessage extends Model
{
    protected $table = 'ReadMessage';

    public function hotesse()
    {
        return $this->belongsTo('App\Hotesse','hotesse_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin','admin_id');
    }

    public function message()
    {
        return $this->belongsTo('App\Admin','message_id');
    }
}