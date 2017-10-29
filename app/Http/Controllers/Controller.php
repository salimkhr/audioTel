<?php

namespace App\Http\Controllers;

use App\AccesAPI;
use App\API;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function testLogin()
    {
        if(!Auth::check())
        Auth::shouldUse("web_admin");
        return(Auth::check());
        date_default_timezone_set('America/New_York');
    }

    public function testLoginAdmin()
    {
        Auth::shouldUse("web_admin");
        return(Auth::check());
    }

    public function loginApi(Request $request)
    {
        $api_id = $request->header('PHP_AUTH_USER');
        $api_key = $request->header('PHP_AUTH_PW');

        $api = API::where("id","=",$api_id)->where("cle","=",$api_key)->where("active","=",true)->first();

        if($api == null)
            return response()->json('Invalid credentials', 401);
        else
        {
            $acces = new AccesAPI();

            $acces->id_API=$api->id;
            $acces->IP=$request->ip();
            $acces->URL=$request->url();
            $acces->methode=$request->method();

            $acces->save();
            return $acces;
        }
    }

    public function RandomString($size =10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $size; $i++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }
}
