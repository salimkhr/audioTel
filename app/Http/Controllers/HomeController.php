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
     *
     * @return void
     */

    public function index()
    {
        $this->login();
        if(Auth::user() instanceof Hotesse)
        {
            $appelToday = Appel::where("admin_id","=",Auth::user()->admin->id)->whereDate('debut',date("Y-m-d"))->get();
            $appels=Appel::where("hotesse_id","=",Auth::id())->get();
        }

        if(Auth::user() instanceof Admin)
        {
            $appelToday = Appel::where("admin_id","=",Auth::id())->whereDate('debut',date("Y-m-d"))->get();
            $appels=Appel::where("admin_id","=",Auth::id())->get();
        }

        $dureeAppel=0;
        $nbAppel=0;
        foreach($appelToday as $appel)
        {
            $dureeAppel+=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $nbAppel++;
        }

        return view('index')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co",">","0")->where("admin_id","=",Auth::id())->get()->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("appels",$appels);
    }
}
