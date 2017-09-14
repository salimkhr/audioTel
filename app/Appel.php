<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 13/09/17
 * Time: 15:59
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Appel extends Model
{
    protected $table = 'appel';

    public function hotesse()
    {
        return $this->belongsTo('App\Hotesse');
    }
}