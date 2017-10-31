<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Appel;
use App\Client;
use App\Code;
use App\Hotesse;
use App\Http\Controllers\Auth\LoginController;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     */

    public function index($debut=null,$fin=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {
            return redirect()->route("getHotesse",["id"=>Auth::id()]);
        }
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
        $appels=Appel::where("admin_id","=",Auth::id())->where('debut',">=",$dateDebut->format('Y-m-d'))->where("debut","<=",$dateFin->format('Y-m-d'))->orderBy("debut")->get();
        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appels as $appel)
        {
            if($appel->type == 1)
            {
                $appelTmp = Appel::where("asterisk_id","=",$appel->link_id)->first();
                if($appelTmp != null)
                {
                    $appel->tarif = $appelTmp->tarif;
                    $appel->client_id=$appelTmp->client_id;
                    $appel->appellant=$appelTmp->appellant;
                    //$appel->code=$appelTmp->code;
                }
            }
            if(isset($appel->tarif->prixMinute))
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            if(isset($appel->tarif->prixMinute))
                $appel->ca=$duree*$appel->tarif->prixMinute;

                $ca+=$appel->ca;
        }

        foreach ($appels as $appel)
        {
            $appel->client=Client::where("tel","=","33".substr($appel->appellant,1))->first();
        }

        $hotesses = Hotesse::where("admin_id","=",Auth::id())->where("co","=","1")->get();

        return view('index')->with("hotesses",$hotesses)
            ->with("nbHotesseCo",Hotesse::where("co",">","0")->where("admin_id","=",Auth::id())->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("debut",$debut)
            ->with("fin",$fin);
    }
}
