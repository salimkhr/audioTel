<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 10/09/17
 * Time: 13:03
 */

namespace App\Http\Controllers;


use App\API;
use App\Client;
use App\Credit;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Mockery\Exception;

class ClientController extends Controller
{
    public function client()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

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

    public function APIindex($cle)
    {
        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);

        $clients = Client::all();
        $jsonClient = array();

        foreach ($clients as $client) {
            $montant = 0;
            $trans = Credit::where('client_id', '=', $client->id)->get();
            foreach ($trans as $tran) {
                $montant += $tran->montant;
            }
            $client->solde = $montant;
            array_push($jsonClient, ["client" => ["id" => $client->id, "credit" => $client->solde]]);
        }

        return response()->json($jsonClient);
    }

    public function getFormClient($id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            return view('client.new')->with("client",Client::find($id));
        else
            return view('client.new')->with("client",new Client());
    }

    public function postFormClient(ClientRequest $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            $client = Client::find($id);
        else
        {
            $client = new Client;
        }

        $client->code=$request->input('code');
        $client->save();

        return redirect()->route('client');
    }

    public function APIget($cle,$id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);

        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);
        $client = Client::find($id);

        if($client == null)
            return response()->json(null);

        $montant=0;
        $trans = Credit::where('client_id', '=', $client->id)->get();
        foreach ($trans as $tran) {
            $montant += $tran->montant;
        }
        $client->solde = $montant;

        return response()->json(["id"=>$client->id, "code"=>$client->code, "solde"=>$client->solde]);
    }

    public function APIpost(Request $request,$cle,$id)
    {
        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);

        $client = Client::find($id);

        if($client == null)
            return response()->json("failed");

        try{
            $credit = new Credit();
            $credit->montant=$request->input("credit");
            $credit->client_id=$client->id;
            $credit->save();
            return response()->json("success");
        }
        catch(Exception $e)
        {
            return response()->json("failed");
        }
    }
}