<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PhotoHotesse extends Model
{
    use SoftDeletes;
    protected $table = 'photoHotesse';

}
