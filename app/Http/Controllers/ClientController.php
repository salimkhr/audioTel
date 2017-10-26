<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 10/09/17
 * Time: 13:03
 */

namespace App\Http\Controllers;


use App\AccesAPI;
use App\API;
use App\Appel;
use App\Client;
use App\Credit;
use App\Http\Requests\ClientRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ClientController extends Controller
{
    public function client()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $clients = Client::where('admin_id','=',Auth::id())->orderByDesc("created_at")->get();

        foreach ($clients as $client)
        {
            $montant=0;
            $trans = Credit::where('client_id', '=', $client->id)->get();
            foreach ($trans as $tran) {
                $montant += $tran->temps;
            }
            $client->solde = $montant/60%60;
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

        $appels = $client->appel;
        $credits = $client->credit;

        $nbAppel=count($appels);
        $nbCredit = count($credits);

        $cptAppel=0;
        $cptCredit=0;

        $listEvent=[];

        if(isset($credits[$cptCredit]))
            $dateCredit=new \DateTime($credits[$cptCredit]->created_at);
        else
            $cptCredit = new \DateTime();;

        if(isset($appels[$cptAppel]))
            $dateAppel=new \DateTime($appels[$cptAppel]->debut);
        else
            $dateAppel = new \DateTime();;

        $montant=0;
        $trans = Credit::where('client_id', '=', $client->id)->get();
        foreach ($trans as $tran) {
            $montant += $tran->temps;
        }
        $client->solde = $montant/60.%60.;

        for($i=0;$i<$nbAppel+$nbCredit;$i++)
        {
           if($dateCredit != null &&  $dateAppel != null && $dateCredit->format('U') < $dateAppel->format('U'))
           {
               if(isset($credits[$cptCredit]))
               {
                   array_push($listEvent,$credits[$cptCredit]);
                   $cptCredit++;

                   if(isset($credits[$cptCredit]))
                       $dateCredit=new \DateTime($credits[$cptCredit]->created_at);
                   else
                       $dateCredit = new \DateTime();
               }

           }
           else
           {
               if(isset($appels[$cptAppel]))
               {
                   array_push($listEvent,$appels[$cptAppel]);
                   $cptAppel++;
                   if(isset($appels[$cptAppel]))
                       $dateAppel=new \DateTime($appels[$cptAppel]->debut);
                   else
                       $dateAppel = new \DateTime();
               }
           }
        }

        return view("client.index")->with("client",$client)->with("listEvent",array_reverse($listEvent));
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

    public function delete($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        Client::find($id)->delete();
        return redirect()->route('client');
    }

    public function APIgetCredit(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $client =  Client::where("admin_id","=",$api->api->admin_id)->where("id","=",$id)->first();

        if($client == null)
            return response()->json(null);

        $credit = Credit::where("client_id","=",$id)->get(["id","created_at AS date","montant as credit"]);

        return response()->json($credit);
    }

    public function APIget(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $client =  Client::where("admin_id","=",$api->api->admin_id)->where("id","=",$id)->first();

        if($client == null)
            return response()->json(null);
        $montant=0;
        $trans = Credit::where('client_id', '=', $client->id)->get();
        foreach ($trans as $tran) {
            $montant += $tran->temps;
        }
        $client->solde = $montant;

        return response()->json(["id"=>$client->id, "phone"=>$client->tel, "create_date"=>date_format(date_create($client->created_at), 'Y-m-d H:i:s'),"statut"=>$client->active?"Activé":"Désactivé", "solde"=>($client->solde)]);
    }

    public function APIpostCredit(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $client =  Client::where("admin_id","=",$api->api->admin_id)->where("id","=",$id)->first();


        if($client == null)
            return response()->json("failed");

        $credit = new Credit();
        $credit->client_id=$id;
        $credit->temps=$request->input("credit");

        try{
            $credit->save();
        }
        catch (QueryException $e){
            $api->resultat=false;
            $api->save;
            return response()->json("failed");
        }
        $api->resultat=true;
        $api->save();
        return response()->json("success");
    }

    public function APIcall(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $client =  Client::where("admin_id","=",$api->api->admin_id)->where("id","=",$id)->first();

        if($client == null)
            return response()->json(null);

        $appels = Appel::where('client_id', '=', $id)->where('admin_id','=',$api->api->admin_id)->get();

        $listAppel=array();

        foreach ($appels as $appel) {
            array_push($listAppel,["date" => $appel->debut,"duree"=>date_diff(date_create($appel->debut),date_create($appel->fin))->format('%I:%S')]);
        }

        return response()->json($listAppel);
    }

    public function APIindex(Request $request)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $clients = Client::where("admin_id","=",$api->api->admin_id)->get();
        $jsonClient = array();

        foreach ($clients as $client) {
            array_push($jsonClient, ["client" => ["id" => $client->id, "phone" => $client->tel]]);
        }

        return response()->json($jsonClient);
    }

    public function APIpost(Request $request)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        try{
            $client = new Client();
            $client->tel=$request->input("phone");
            $client->admin_id=$api->api->admin_id;
            $client->code=rand(100000,999999);
            $client->save();
        }
        catch(QueryException  $e)
        {
            $api->resultat=false;
            $api->save();
            return response()->json(["resultat"=>"failed"]);
        }
        $api->resultat=true;
        $api->save();
        return response()->json(["resultat"=>"success","id"=>$client->id,"code"=>$client->code]);
    }
}