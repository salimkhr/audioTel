<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Appel;
use App\Hotesse;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     */

    public function index()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {
            return redirect()->route("getHotesse",["id"=>Auth::id()]);
        }


        $appelToday = Appel::where("admin_id","=",Auth::id())->where('debut',"=" ,"CURDATE()")->get();
        $appels=Appel::where("admin_id","=",Auth::id())->get();

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

        return view('index')->with("hotesses",Hotesse::where("admin_id","=",Auth::id()))
            ->with("nbHotesseCo",Hotesse::where("co",">","0")->where("admin_id","=",Auth::id())->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels);
    }
}
