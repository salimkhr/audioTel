<?php

namespace App\Http\Controllers;

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

        $api = API::where("id","=",$api_id)->where("cle","=",$api_key)->first();

        if($api == null)
            return response()->json('Invalid credentials', 401);
        else
            return $api;
    }

    public function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }
}
