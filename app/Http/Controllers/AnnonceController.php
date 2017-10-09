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

        $annonces=$codes=null;

        if(Auth::user() instanceof Hotesse)
        {
            $codesTmp = Code::where("hotesse_id","=",Auth::id())->get();
            $annonces = Annonce::where("hotesse_id","=",Auth::id())->get();
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

        $codes=$request->input("code");
        foreach($codes as $idCode)
        {
            $code = Code::find($idCode);
            $code->annonce_id=$id;
            $code->save();
        }
        return redirect()->route("annonce")->with("message","modification effectué avec succès");
    }

    public function delete($id)
    {
        $annonce = Annonce::find($id);

        $code=$annonce->code;
        if($code != null)
        {
            $code->annonce_id=null;
            $code->save();
        }

        $annonce->delete();
        return redirect()->route("annonce");
    }




}