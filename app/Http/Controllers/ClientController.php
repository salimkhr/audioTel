<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 10/09/17
 * Time: 13:03
 */

namespace App\Http\Controllers;


use App\Client;
use App\Credit;

class ClientController extends Controller
{
    public function __construct()
    {
        if(Auth::user() == null)
            Auth::shouldUse("web_admin");

        $this->middleware('auth');
    }

    public function client()
    {
        $clients = Client::all();
        foreach ($clients as $client)
        {
            $montant=0;
            $trans=Credit::where('client_id','=',$client->id)->get();
            foreach ($trans as $tran)
            {
                $montant+=$tran->montant;
            }
            $client->solde=$montant;
        }

        return view('client')->with("clients",$clients);
    }
}