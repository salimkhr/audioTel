<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Annonce;
use App\Code;
use App\Hotesse;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\PhotoRequest;
use App\PhotoCode;
use App\PhotoHotesse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Mockery\Exception;


class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){

        $annonces=[];
        $codes=null;

        if(Auth::user() instanceof Hotesse)
        {
            $codesTmp = Code::where("hotesse_id","=",Auth::id())->get();
            $codes = Code::where("hotesse_id","=",Auth::id())->get();
            foreach($codes as $code){
                $annoncesTmp=Annonce::where("code","=",$code->code)->get();
                foreach ($annoncesTmp as $annonce)
                {
                    array_push($annonces,$annonce);
                }
            }
        }
        if(Auth::user() instanceof Admin)
        {
            $codesTmp = Code::where("admin_id","=",Auth::id())->limit(8)->get();
            $annonces = Annonce::with("Hotesse")->where("admin_id","=",Auth::id())->get();
        }

        $codes=[];

        foreach ($codesTmp as $code)
        {
            $codes[$code->code]=$code->pseudo." (".$code->code.")";
        }

        return view("annonce")->with("annonces",$annonces)->with("codes",$codes);
    }

    public function update(Request $request,$id)
    {
        $annonce = Annonce::find($id);
        $annonce->name=$request->input("name")!=null?$request->input("name"):"";
        $annonce->save();

        return redirect()->back()->with("message","modification effectué avec succès");
    }

    public function delete($id)
    {
        $annonce = Annonce::find($id);
        $annonce->delete();
        return redirect()->back()->with("message","annonce supprimé");
    }




}