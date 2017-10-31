<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Cmgmyr\Messenger\Traits\Messagable;


class Admin extends User
{
    use Notifiable;
    use SoftDeletes;
    use Messagable;

    protected $table = 'admin';
    protected $dates = ['deleted_at'];

    public function photos()
    {
        return $this->hasMany('App\PhotoAdmin');
    }

    public function photo()
    {
        return $this->belongsTo('App\PhotoAdmin','photoAdmin_id');
    }

    public function hotesses()
    {
        return $this->hasMany('App\Hotesse');
    }

    public function messages()
    {
        return $this->hasMany('App\Tchat');
    }

    public function nbMessage()
    {
        $nbMessage= 0;
        foreach ($this->messages as $message)
        {
            if($message->read == 0 && $message->expediteur == "A")
                $nbMessage++;
        }

        return $nbMessage;
    }
}
