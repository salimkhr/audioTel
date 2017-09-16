<?php

namespace App;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
    use Notifiable;
    protected $table = 'admin';
}
