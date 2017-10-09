<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 09/10/17
 * Time: 13:18
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';

    public function hotesse()
    {
        return $this->belongsTo("App\Hotesse");
    }

    public function client()
    {
        return $this->belongsTo("App\Client");
    }
}