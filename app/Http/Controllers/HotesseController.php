<?php

namespace App\Http\Controllers;

use App\Admin;
use App\API;
use App\Code;
use App\Hotesse;
use App\Appel;
use App\Http\Requests\HotesseRequest;
use App\Photo;
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

        $appelToday = Appel::where("hotesse_id","=",$id)->whereDate('debut',date("Y-m-d"))->get();
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

        if(Auth::user() instanceof Admin)
            return view('hotesse.index')->with("hotesses",Hotesse::all())
                ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
                ->with("dureeAppel",$dureeAppel)
                ->with("nbAppel",$nbAppel)
                ->with("appels",$appels)
                ->with("hotesse",Hotesse::find($id));

        return view('index')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels);

    }

    public function getFormHotesse($id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            $hotesse=Hotesse::find($id);
        else
            $hotesse = new Hotesse();

        return view('hotesse.new')->with("hotesse",$hotesse)->with("photos",Photo::all());
    }

    public function postFormHotesse(HotesseRequest $request,$id=null)
    {

        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            $hotesse = Hotesse::find($id);
        else
        {
            $hotesse = new Hotesse;
            $hotesse->password=hash("sha512",$request->input('password'));
        }

        dump($request->input());

        $hotesse->name=$request->input('name');
        $hotesse->photo_id=$request->input('photo_id');
        $hotesse->tel=$request->input('tel');
        $hotesse->admin_id=1;
        $hotesse->save();

        return redirect()->back()->withInput();
    }

    public function activeHotesse($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $hotesse=Hotesse::find($id);
        $hotesse->active =! $hotesse->active;
        $hotesse->save();
        return redirect()->route('hotesse');
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

            return redirect()->route("getUpdateHotesse",["id"=>$id])->withErrors($bag);
        }
    }

    public function codeHotesse($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        return view('code')->with("codes",Code::where("hotesse_id","=",$id)->get());
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