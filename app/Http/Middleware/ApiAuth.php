<?php

namespace App\Http\Middleware;

use Closure;
use App\API;
//use App\Http\Controllers\DataBaseDedimaxController;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $api_id = $request->header('PHP_AUTH_USER');
        $api_key = $request->header('PHP_AUTH_PW');

        $bdd = new API();
        $essai = $bdd->isAuthorized($api_id, $api_key);
        if($essai<1){
            return response('Invalid credentials', 401);
        }

        //mise en session de l'api_id
        session(['api_id' => $api_id]);

        //mise en session de idclient
        $idclient = $bdd->get_idclient_from_api_id();
        session(['idclient' => $idclient]);

        return $next($request);
    }
}