<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 10/09/17
 * Time: 13:02
 */

namespace App\Http\Controllers;


use App\Admin;
use App\Annonce;
use App\Code;
use App\Hotesse;
use App\Http\Requests\CodeRequest;
use App\Photo;
use Illuminate\Support\Facades\Auth;

class CodeController extends Controller
{

    public function index()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {
            $codes = Code::where("hotesse_id","=",Auth::id())->get();
        }
        if(Auth::user() instanceof Admin)
        {
            $codes = Code::where("admin_id","=",Auth::id())->get();
        }

        return view('code')->with("codes",$codes);
    }

    public function code($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");
        return view("code.code")->with("code",Code::find($id));
    }

    public function getFormCode($id=null)
    {
        $hotesses=Hotesse::all();
        $dataHotesse=[];
        $dataHotesse[-1]="Aucune Hotesse";
        foreach($hotesses as $hotesse)
        {
            $dataHotesse[$hotesse->id]=$hotesse->name;
        }

        $annonces=Annonce::all();
        $dataAnnonce=[];
        $dataAnnonce[-1]="Aucune annonce";
        foreach($annonces as $annonce)
        {
            $dataAnnonce[$annonce->id]=$annonce->file." ".(($annonce->name!="")?"(".$annonce->name.")":"");
        }

        if(isset($id))
        {
            $code=Code::find($id);
            $photo=Photo::where('code','=',null)->orWhere('code','=',$code->code)->get();
        }
        else
        {
            $code=new Code();
            $photo=Photo::where('code','=',null)->get();
        }

        return view('code.new')->with("hotesses",$dataHotesse)->with("annonces",$dataAnnonce)->with("photos",$photo)->with("code",$code);
    }

    public function postFormCode(CodeRequest $request,$id=null)
    {
        if(isset($id))
            $code = code::find($id);
        else
            $code = new Code();

        $code->code=$request->input('code');
        $code->pseudo=$request->input('pseudo');
        $code->description=$request->input('description');

        if($request->input('hotesse_id') != -1)
            $code->hotesse_id=$request->input('hotesse_id');
        if($request->input('annonce_id') != -1)
            $code->annonce_id=$request->input('annonce_id');

        $code->save();

        return redirect()->route('codeAdmin');
    }

    public function activeCode($id)
    {
        $code=Code::find($id);
        $code->active =! $code->active;
        $code->save();
        return redirect()->route('code');
    }
    public function deleteCode($id)
    {
        Code::find($id)->delete();
        return redirect()->route('code');
    }
}