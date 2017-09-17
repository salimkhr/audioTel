<?php

namespace App\Http\Controllers;

use App\Hotesse;
use App\Appel;
use App\Http\Requests\HotesseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        foreach($appelToday as $appel)
        {
            $dureeAppel+=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $nbAppel++;
        }

        return view('index')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("appels",$appels);
    }

    public function getFormHotesse($id=null)
    {
        
        if(isset($id))
            return view('hotesse.new')->with("hotesse",Hotesse::find($id));
        else
            return view('hotesse.new')->with("hotesse",new Hotesse());
    }

    public function postFormHotesse(HotesseRequest $request,$id=null)
    {
        
        if(isset($id))
            $hotesse = Hotesse::find($id);
        else
        {
            $hotesse = new Hotesse;
            $hotesse->password=hash("sha512",$request->input('password'));
        }

        $hotesse->name=$request->input('name');
        $hotesse->tel=$request->input('tel');
        $hotesse->admin_id=1;
        $hotesse->save();

        return redirect()->route('hotesse');
    }

    public function activeHotesse($id)
    {
        
        $hotesse=Hotesse::find($id);
        $hotesse->active =! $hotesse->active;
        $hotesse->save();
        return redirect()->route('hotesse');
    }

    public function deleteHotesse($id)
    {
        
        Hotesse::find($id)->delete();
        return redirect()->route('hotesse');
    }
}