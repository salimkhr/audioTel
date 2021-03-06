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

    public function getcode()
    {
        return $this->belongsTo('App\Code',"code");
    }

    public function tarif()
    {
        return $this->belongsTo('App\Tarif',"pays");
    }

    public function getClient()
    {
        return $this->belongsTo('App\Client',"client_id");
    }
}