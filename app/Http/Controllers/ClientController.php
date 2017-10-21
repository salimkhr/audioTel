<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 10/09/17
 * Time: 13:03
 */

namespace App\Http\Controllers;


use App\API;
use App\Appel;
use App\Client;
use App\Credit;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ClientController extends Controller
{
    public function client()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $clients = Client::where('admin_id','=',Auth::id())->get();
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

    public function getClient($id){

        if(!$this->testLogin())
            return redirect()->route("login");

        $client=Client::find($id);
        $montant=0;

        $trans = Credit::where('client_id', '=', $id)->get();
        foreach ($trans as $tran) {
            $montant += $tran->montant;
        }
        $client->solde = $montant;
        return view("client.index")->with("client",$client);
    }

    public function activeClient($id)
    {
        if(!$this->testLoginAdmin())
            return redirect()->route("login");

        $client=Client::find($id);
        $client->active =! $client->active;
        $client->save();
        return redirect()->back()->withInput()->with('message', $client->code." à été ".($client->active?"activé":"désactivé"));
    }


    public function getFormClient($id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            $client=Client::find($id);
        else
        {
            $client=new Client();
            $client->code=rand(0,10000);
        }

        return view('client.new')->with("client",$client);
    }

    public function postFormClient(Request $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
        {
            $client = Client::find($id);
            $message="remarque modifié";
        }
        else
        {
            $client = new Client;
            $client->admin_id=Auth::id();
            $client->code=$request->input('code');
            $message="client ajouté";
        }

        $client->remarque=$request->input('remarque');
        $client->save();

        return redirect()->route("client")->with("message",$message);
    }

    public function APIgetCredit(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof API)
            return $api;

        $client =  Client::where("admin_id","=",$api->admin_id)->where("id","=",$id)->first();

        if($client == null)
            return response()->json(null);

        $credit = Credit::where("client_id","=",$id)->get(["id","created_at AS date","montant as credit"]);

        return response()->json($credit);
    }

    public function APIpostCredit(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof API)
            return $api;

        $client =  Client::where("admin_id","=",$api->admin_id)->where("id","=",$id)->first();


        if($client == null)
            return response()->json("failed");

        $credit = new Credit();
        $credit->client_id=$id;
        $credit->montant=$request->input("credit");

        try{
            $credit->save();
        }
        catch (\Error $e){
            return response()->json("failed");
        }
        return response()->json("success");
    }

    public function APIcall(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof API)
            return $api;

        $client =  Client::where("admin_id","=",$api->admin_id)->where("id","=",$id)->first();

        if($client == null)
            return response()->json(null);

        $appels = Appel::where('client_id', '=', $id)->where('admin_id','=',$api->admin_id)->get();

        $listAppel=array();

        foreach ($appels as $appel) {
            array_push($listAppel,["date" => $appel->debut,"duree"=>date_diff(date_create($appel->debut),date_create($appel->fin))->format('%I:%S')]);
        }

        return response()->json($listAppel);
    }

    public function APIindex(Request $request)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof API)
            return $api;

        $clients = Client::where("admin_id","=",$api->admin_id)->get();
        $jsonClient = array();

        foreach ($clients as $client) {
            array_push($jsonClient, ["client" => ["id" => $client->id, "phone" => $client->tel]]);
        }

        return response()->json($jsonClient);
    }

    public function APIpost(Request $request)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof API)
            return $api;

        try{
            $client = new Client();
            $client->tel=$request->input("phone");
            $client->admin_id=$api->admin_id;
            $client->code=rand()+999;
            $client->save();
        }
        catch(Exception $e)
        {
            return response()->json(["resultat"=>"failed"]);
        }

        return response()->json(["resultat"=>"success","id"=>$client->id]);
    }
}