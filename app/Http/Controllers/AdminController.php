<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Appel;
use App\Hotesse;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appelToday = Appel::where("admin_id","=",Auth::guard("web_admin")->id())->whereDate('debut',date("Y-m-d"))->get();

        $dureeAppel=0;
        foreach($appelToday as $appel)
        {
            $dureeAppel+=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
        }

        return view('admin.index')->with("hotesses",Hotesse::all())
        ->with("nbHotesseCo",Hotesse::where("co",">","0")->where("admin_id","=",Auth::guard("web_admin")->id())->get()->count())
        ->with("dureeAppel",$dureeAppel)
        ->with("appels",Appel::where("admin_id","=",Auth::guard("web_admin")->id())->get());
    }


    public function admin()
    {
       return view('admin.admin')->with("admins",Admin::all());
    }
}