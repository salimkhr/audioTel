<?php

namespace App\Http\Controllers;

use App\AccesAPI;
use App\Admin;
use App\API;
use App\Code;
use App\Hotesse;
use App\Appel;
use App\Http\Requests\HotesseRequest;
use App\PhotoCode;
use App\PhotoHotesse;
use DateTime;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class HotesseController extends Controller
{

    public function index($page=1,$idAdmin=null,$search=null)
    {
        if(!$this->testLoginAdmin())
            return redirect()->route("login");

        if($idAdmin==null)
            $idAdmin = Auth::id();


        if(Auth::user()->role == "superAdmin")
        {
            $listAdmin=Admin::where("admin_id","=",Auth::id())->get(['id']);
            $listId=[Auth::id()];

            foreach ($listAdmin as $admin)
            {
                array_push($listId,$admin->id);
            }

            $hotesse = Hotesse::wherein("admin_id",$listId);

            if($search!=null)
                $hotesse->where("name","like","%".$search."%");

            $nbHotesses=$hotesse->count();
            $hotesses=$hotesse->limit(8)->offset(8*($page-1))->get();


            /*$hotesses = Hotesse::where("admin_id","=",$idAdmin)->limit(8)->offset(8*($page-1))->get();
            $nbHotesses = Hotesse::where("admin_id","=",$idAdmin)->count();*/
        }
        else
        {
            $hotesse = Hotesse::where("admin_id","=",$idAdmin);
            if($search!=null)
                $hotesse->where("name","like","%".$search."%");

            $hotesses=$hotesse->limit(8)->offset(8*($page-1))->get();
            $nbHotesses = $hotesse->count();
        }


        foreach ($hotesses as $hotesse)
        {
            $hotesse->disponible = false;

            foreach ($hotesse->code as $code)
            {
                if($code->dispo)
                    $hotesse->dispo = true;
                break;
            }
            if($hotesse->disponible)
                break;
        }
        if($nbHotesses %8 !=0)
        {
            $nbHotesses=($nbHotesses/8)+1;
        }
        else
        {
            $nbHotesses=($nbHotesses/8);
        }
        return view('hotesse')->with("hotesses",$hotesses)->with("nbCode",$nbHotesses)->with("page",$page)->with("idAdmin",$idAdmin)->with("search",$search);
    }

    public function hotesse($id,$debut=null,$fin=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse && Auth::id() != $id)
            abort(403, 'Unauthorized action.');

        if($debut == null)
        {
            $dateDebut= new DateTime();
            $dateFin= new DateTime();
        }
        else{
            $dateDebut=new DateTime($debut);
            $dateFin=new DateTime($fin);

        }
        $dateFin->modify('+1 day');
        $appels=Appel::where("hotesse_id","=",$id)->where('debut',">=",$dateDebut->format('Y-m-d'))->where("debut","<=",$dateFin->format('Y-m-d'))->get();
        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appels as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            switch ($appel->pays)
            {
                case "FR" : $ca+=$duree*Hotesse::find($id)->tarif_FR;break;
                case "BE" : $ca+=$duree*Hotesse::find($id)->tarif_BE;break;
                case "CH" : $ca+=$duree*Hotesse::find($id)->tarif_CH;break;
            }

        }

        return view('index')->with("hotesses",Code::where("hotesse_id","=",$id)->where("dispo","=",1)->get())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("debut",$debut)
            ->with("fin",$fin);

    }

    public function hotesseAdmin($id,$debut=null,$fin=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");


        if(Auth::user() instanceof Hotesse && Auth::id() != $id)
            abort(403, 'Unauthorized action.');

        if($debut == null)
        {
            $dateDebut= new DateTime();
            $dateFin= new DateTime();
        }
        else{
            $dateDebut=new DateTime($debut);
            $dateFin=new DateTime($fin);

        }
        $dateFin->modify('+1 day');
        $appels=Appel::where("hotesse_id","=",$id)->where('debut',">=",$dateDebut->format('Y-m-d'))->where("debut","<=",$dateFin->format('Y-m-d'))->get();


        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appels as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            dump($appel->pays);
            switch ($appel->pays)
            {
                case "FR" : $ca+=$duree*Hotesse::find($id)->tarif_FR;break;
                case "BE" : $ca+=$duree*Hotesse::find($id)->tarif_BE;break;
                case "CH" : $ca+=$duree*Hotesse::find($id)->tarif_CH;break;
            }
        }

        return view('hotesse.admin')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("hotesse",Hotesse::find($id))
            ->with("debut",$debut)
            ->with("fin",$fin);

    }

    public function getFormHotesse($id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
        {
            $hotesse=Hotesse::find($id);
            $photos = PhotoHotesse::where("hotesse_id","=",$id)->get();
        }
        else
        {
            $hotesse = new Hotesse();

            if(Auth::user() instanceof Hotesse)
                $photos = PhotoHotesse::where("hotesse_id","=",$id)->get();
            else
                $photos = PhotoHotesse::where("admin_id","=",Auth::id())->get();
        }

        return view('hotesse.new')->with("hotesse",$hotesse)->with("photos",$photos)->with("password",$this->RandomString());
    }

    public function postFormHotesse(HotesseRequest $request, $id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
        {
            $hotesse = Hotesse::find($id);
            $message="modification effectué avec succès";
        }
        else
        {
            $hotesse = new Hotesse;
            $hotesse->password=hash("sha512",$request->input('password'));
            $message="ajout effectué avec succès";
        }

        $hotesse->name=$request->input('name');

        if($request->input('photo_id')!=null)
            $hotesse->photoHotesse_id=$request->input('photo_id');
        else
            $hotesse->photoHotesse_id=1;

        $hotesse->tel=$request->input('tel');

        $hotesse->tarif_FR=$request->input('tarif_FR');
        $hotesse->tarif_BE=$request->input('tarif_BE');
        $hotesse->tarif_CH=$request->input('tarif_CH');

        if(Auth::user() instanceof Admin)
            $hotesse->admin_id=Auth::id();
        $hotesse->save();

        if(Auth::user() instanceof Hotesse)
            return redirect()->route("getHotesse",["id"=>$hotesse->id])->withInput()->with('message', $message);

        return redirect()->route("hotesse")->withInput()->with('message', $message);
    }

    public function activeHotesse($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $hotesse=Hotesse::find($id);
        $hotesse->active =! $hotesse->active;
        $hotesse->save();
        return redirect()->back()->withInput()->with('message', $hotesse->name." a été ".($hotesse->active?"activée":"désactivée"));
    }

    public function deleteHotesse($id)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        try {
            Hotesse::find($id)->delete();
            return redirect()->route('hotesse');
        }
        catch (QueryException $e)
        {
            $bag = new MessageBag();
            $bag->add("err","une erreur s'est produite pendant la suppression");

            return redirect()->back()->withErrors($bag);
        }
        return redirect()->back()->with('message', "l'hotesse a bien été suprimer ");
    }

    public function codeHotesse($id,$page=0)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $codes = Code::where("hotesse_id","=",$id)->limit(8)->offset(8*($page-1))->get();
        $nbCodes = Code::where("hotesse_id","=",$id)->count();

        if($nbCodes %8 !=0)
        {
            $nbCodes=($nbCodes/8)+1;
        }
        else
        {
            $nbCodes=($nbCodes/8);
        }

        return view('code')->with("codes",$codes)->with("nbCode",$nbCodes)->with("page",$page)->with("hotesse",$id);
    }

    public function APIindex(Request $request)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $hotesses = Hotesse::where("admin_id","=",$api->api->admin_id)->get();
        $jsonHotesse = array();

        foreach ($hotesses as $hotesse)
            array_push($jsonHotesse,["hotesse"=>["id"=>$hotesse->id,"username"=>$hotesse->name]]);

        return response()->json($jsonHotesse);
    }

    public function APIget(Request $request,$id)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $hotesse = Hotesse::where("id","=",$id)->where("admin_id","=",$api->api->admin_id)->first();

        if($hotesse==null)
            return response()->json(null);

        $listCode=array();

        foreach ($hotesse->code as $code)
            array_push($listCode,$code->code);

        return response()->json(["id"=>$hotesse->id,"username"=>$hotesse->name,"code"=>$listCode]);
    }
}