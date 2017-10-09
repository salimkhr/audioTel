<?php

namespace App\Http\Controllers;

use App\Admin;
use App\API;
use App\Code;
use App\Hotesse;
use App\Appel;
use App\Http\Requests\HotesseRequest;
use App\PhotoCode;
use App\PhotoHotesse;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class HotesseController extends Controller
{

    public function index()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $hotesses=Hotesse::all();
        return view('hotesse')->with("hotesses",$hotesses);
    }

    public function hotesse($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse && Auth::id() != $id)
            abort(403, 'Unauthorized action.');

        $appelToday = Appel::where("hotesse_id","=",$id)->where('debut',"=" ,"CURDATE()")->get();
        $appels=Appel::where("hotesse_id","=",$id)->get();

        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appelToday as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            if(isset($appel->tarif->prixMinute))
                $ca+=$duree*$appel->tarif->prixMinute;
        }

        return view('index')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels);

    }

    public function hotesseAdmin($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse && Auth::id() != $id)
            abort(403, 'Unauthorized action.');

        $appelToday = Appel::where("hotesse_id","=",$id)->where('debut',"=" ,"CURDATE()")->get();
        $appels=Appel::where("hotesse_id","=",$id)->get();

        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appelToday as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            if(isset($appel->tarif->prixMinute))
                $ca+=$duree*$appel->tarif->prixMinute;
        }

        return view('hotesse.admin')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("hotesse",Hotesse::find($id));

    }

    public function getFormHotesse($id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            $hotesse=Hotesse::find($id);
        else
            $hotesse = new Hotesse();

        if(Auth::user() instanceof Hotesse)
            $photos = PhotoHotesse::where("hotesse_id","=",$id)->get();
        else
            $photos = PhotoHotesse::where("admin_id","=",Auth::id())->get();

        return view('hotesse.new')->with("hotesse",$hotesse)->with("photos",$photos)->with("password",$this->RandomString());
    }

    /**
     * @param HotesseRequest $request
     * @param null $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
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
        $hotesse->photoHotesse_id=$request->input('photo_id');
        $hotesse->tel=$request->input('tel');
        $hotesse->admin_id=1;
        $hotesse->save();

        return redirect()->back()->withInput()->with('message', $message);
    }

    public function activeHotesse($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $hotesse=Hotesse::find($id);
        $hotesse->active =! $hotesse->active;
        $hotesse->save();
        return redirect()->back()->withInput()->with('message', $hotesse->name." à été ".($hotesse->active?"activé":"désactivé"));
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
        $nbCode = Code::where("hotesse_id","=",$id)->count();

        return view('code')->with("codes",$codes)->with("nbCode",$nbCode)->with("page",$page);
    }


    public function APIindex($cle)
    {
        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);

        $hotesses = Hotesse::all();
        $jsonHotesse = array();

        foreach ($hotesses as $hotesse)
            array_push($jsonHotesse,["hotesse"=>["id"=>$hotesse->id,"username"=>$hotesse->name]]);

        return response()->json($jsonHotesse);
    }

    public function APIget($cle,$id)
    {
        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);

        $hotesse = Hotesse::find($id);
        if($hotesse==null)
            return response()->json(null);

        $listCode=array();
        foreach ($hotesse->code as $code)
            array_push($listCode,$code->code);

        return response()->json(["id"=>$hotesse->id,"username"=>$hotesse->name,"code"=>$listCode]);
    }
}