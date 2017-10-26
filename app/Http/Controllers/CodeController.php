<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 10/09/17
 * Time: 13:02
 */

namespace App\Http\Controllers;


use App\AccesAPI;
use App\Admin;
use App\Annonce;
use App\API;
use App\Appel;
use App\Code;
use App\Hotesse;
use App\Http\Requests\CodeRequest;
use App\PhotoHotesse;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class CodeController extends Controller
{
    public function index($page=1,$idAdmin =null,$search=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Admin)
        {
            if($idAdmin==null)
                $idAdmin = Auth::id();

            if(Auth::user()->role == "superAdmin" && $idAdmin==Auth::id())
            {
                $listAdmin=Admin::where("admin_id","=",Auth::id())->get(['id']);
                $listId=[Auth::id()];

                foreach ($listAdmin as $admin)
                {
                    array_push($listId,$admin->id);
                }

                $codes = Code::wherein("admin_id",$listId)->where("pseudo","like","%".$search."%")->orWherein("admin_id",$listId)->where("code","like","%".$search."%")->limit(8)->offset(8*($page-1))->orderByDesc("created_at")->get();
                $nbCodes = Code::wherein("admin_id",$listId)->where("pseudo","like","%".$search."%")->orWherein("admin_id",$listId)->where("code","like","%".$search."%")->count();

            }
            else
            {
                $codes= Code::where("admin_id","=",$idAdmin)->where("pseudo","like","%".$search."%")->orWhere("admin_id","=",$idAdmin)->where("code","like","%".$search."%")->limit(8)->offset(8*($page-1))->orderByDesc("created_at")->get();
                $nbCodes= Code::where("admin_id","=",$idAdmin)->where("pseudo","like","%".$search."%")->orWhere("admin_id","=",$idAdmin)->where("code","like","%".$search."%")->count();
            }
        }
        else
        {
            $search = $idAdmin;
            $idAdmin=null;

            $codes = Code::where("hotesse_id","=",Auth::id())->where("pseudo","like","%".$search."%")->orWhere("hotesse_id","=",Auth::id())->where("code","like","%".$search."%")->limit(8)->offset(8*($page-1))->orderByDesc("created_at")->get();
            $nbCodes = Code::where("hotesse_id","=",Auth::id())->where("pseudo","like","%".$search."%")->orWhere("hotesse_id","=",Auth::id())->where("code","like","%".$search."%")->count();
        }

        if($nbCodes %8 !=0)
        {
            $nbCodes=($nbCodes/8)+1;
        }
        else
        {
            $nbCodes=($nbCodes/8);
        }

        return view('code')->with("codes",$codes)->with("nbCode",$nbCodes)->with("page",$page)->with("idAdmin",$idAdmin)->with("search",$search);
    }

    public function reportingCode($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");
        return view("code.report")->with("code",Code::find($id));
    }

    public function getFormCode($id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $hotesses=Hotesse::where("admin_id","=",Auth::id())->get();
        $dataHotesse=[];
        $dataHotesse[-1]="Aucune Hotesse";
        foreach($hotesses as $hotesse)
        {
            $dataHotesse[$hotesse->id]=$hotesse->name;
        }

        if(isset($id))
        {
            $code=Code::find($id);
            if(Auth::user() instanceof Hotesse)
                $photo=PhotoHotesse::where("hotesse_id","=",Auth::id())->get();
            else
                $photo=PhotoHotesse::where("admin_id","=",Auth::id())->get();
        }
        else {
            $code = new Code();
            do
                $code->code=rand(1000,9999);
            while($code::find($code->code)!=null);
            $photo = PhotoHotesse::where('admin_id','=',Auth::id())->get();
        }

        return view('code.new')->with("hotesses",$dataHotesse)->with("photos",$photo)->with("code",$code);
    }

    public function reporting($id,$debut=null,$fin=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

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
        $appels=Appel::where("code","=",$id)->where('debut',">=",$dateDebut->format('Y-m-d'))->where("debut","<=",$dateFin->format('Y-m-d'))->get();


        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appels as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            if(isset($appel->tarif->prixMinute))
                $ca+=$duree*$appel->tarif->prixMinute;
        }

        return view('code.report')->with("hotesses",Hotesse::all())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("code",Code::find($id))
            ->with("debut",$debut)
            ->with("fin",$fin);
    }

    public function postFormCode(CodeRequest $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");
        $code = code::find($id);
        if($code != null)
        {
            $message="modification effectuée avec succès";
        }
        else
        {
            $code = new Code();
            $code->admin_id=Auth::id();
            $message="ajout effectué avec succès";
        }

        $code->photoHotesse_id=$request->input('photo_id')!=null?$request->input('photo_id'):1;
        $code->code=$request->input('code');
        $code->pseudo=$request->input('pseudo');
        $code->description=$request->input('description');

        if(Auth::user() instanceof Admin)
            if($request->input('hotesse_id') != -1)
                $code->hotesse_id=$request->input('hotesse_id');
            else
                $code->hotesse_id=null;
        $code->annonce_id=$request->input('annonce_id');

        $code->save();

        /*$photos=PhotoCode::where('code','=',null)->orWhere('code','=',$code->code)->get();

        foreach ($photos as $photo)
        {
                $photo->code=($request->input("photo".$photo->id)!=null)?$id:null;
                $photo->save();
        }*/
        return redirect()->route("code")->with("message",$message);
    }

    public function activeCode($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $code=Code::find($id);

        $this->activeCodePv($code,!$code->dispo);
        return redirect()->back()->with("message","le code a été ".($code->dispo?"connecté":"déconnecté"));
    }

    public function bockCode($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $code=Code::find($id);

        $code->active=!$code->active;
        $code->dispo=$code->active;

        if($code->hotesse==null)
            $code->dispo=false;

        $code->save();
        return redirect()->back()->with("message","le code a été ".($code->dispo?"activé":"désactivé"));
    }

    public function activeAllCode($idAdmin)
    {
        $codes = Code::where("Admin_id","=",$idAdmin)->get();
        foreach ($codes as $code)
            $this->activeCodePv($code,true);

        return redirect()->back()->with("message","tous les codes ont été connectés");
    }

    public function desactiveAllCode($idAdmin)
    {
        $codes = Code::where("Admin_id","=",$idAdmin)->get();
        foreach ($codes as $code)
            $this->activeCodePv($code,false);

        return redirect()->back()->with("message","tous les codes ont été déconnectés");
    }

    private function activeCodePv(Code $code,$active)
    {
        if($code->hotesse != null && $code->active) {
            $code->dispo = $active;
            $code->derniere_connection = date_create();
            $code->save();
        }
    }

    public function deleteCode($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        try {
            Code::find($id)->delete();
            return redirect()->route('code');
        }
        catch (QueryException $e)
        {
            $bag = new MessageBag();
            ///$bag->add("err","une erreur s'est produite pendant la suppression");
            $bag->add("err",$e->getMessage());

            return redirect()->route("getUpdateCode",["id"=>$id])->withErrors($bag);
        }
    }

    public function APIget(Request $request,$code)
    {
        $api = $this->loginApi($request);

        if (!$api instanceof AccesAPI)
            return $api;

        $code = Code::where("code","=",$code)->where("admin_id","=",$api->api->admin_id)->first();
        if($code==null)
            return response()->json(null);


        return response()->json(["code"=>$code->code,"pseudo"=>$code->pseudo,"description"=>$code->description,"photo"=>$code->getPhoto!=null?url(elixir("images/catalog/".$code->getPhoto->file)):null,"statut"=>$code->dispo?"Connecté":"Déconnecté","annonce"=>$code->annonce!=null ? url(elixir("audio/annonce/".$code->annonce->file)) : null]);
    }
}