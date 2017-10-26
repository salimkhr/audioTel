<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class API extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'API';
    protected $dates = ['deleted_at'];
    protected $casts = ['id' => 'string'];
    public $incrementing = false;

}
