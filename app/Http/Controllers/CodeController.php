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
use App\API;
use App\Code;
use App\Hotesse;
use App\Http\Requests\CodeRequest;
use App\PhotoCode;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class CodeController extends Controller
{

    public function index($page=1)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {
            $codes = Code::where("hotesse_id","=",Auth::id())->limit(8)->offset(8*($page-1))->get();
            $nbCodes = Code::where("hotesse_id","=",Auth::id())->count();
        }
        if(Auth::user() instanceof Admin)
        {
            $codes = Code::where("admin_id","=",Auth::id())->limit(8)->offset(8*($page-1))->get();
            $nbCodes = Code::where("admin_id","=",Auth::id())->count();
        }

        if($nbCodes %8 !=0)
        {
            $nbCodes=($nbCodes/8)+1;
        }
        else
        {
            $nbCodes=($nbCodes/8);
        }

        return view('code')->with("codes",$codes)->with("nbCode",$nbCodes)->with("page",$page);
    }

    public function code($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");
        return view("code.code")->with("code",Code::find($id));
    }

    public function getFormCode($id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $hotesses=Hotesse::where("admin_id","=",Auth::id())->get();
        $dataHotesse=[];
        $dataHotesse[-1]="Aucune Hotesse";
        foreach($hotesses as $hotesse)
        {
            $dataHotesse[$hotesse->id]=$hotesse->name;
        }

        if(isset($id))
        {
            $code=Code::find($id);
            $photo=PhotoCode::where('code','=',null)->orWhere('code','=',$code->code)->get();
            $annonces=$code->hotesse->annonces;
        }
        else {
            $code = new Code();
            do
                $code->code=rand(0,10000);
            while($code::find($code->code)!=null);
            $photo = PhotoCode::where('code', '=', null)->get();

            $annonces=[];
            foreach($hotesses as $hotesse)
            {
                foreach ($hotesse->annonces as $annonce)
                    array_push($annonces,$annonce);
            }
        }

        return view('code.new')->with("hotesses",$dataHotesse)->with("photos",$photo)->with("code",$code)->with("annonces",$annonces);
    }

    public function postFormCode(CodeRequest $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");
        $code = code::find($id);
        dump($id);
        if($code != null)
        {
            $message="modification effectué avec succès";
        }
        else
        {
            $code = new Code();
            $code->admin_id=Auth::id();
            $message="ajout effectué avec succès";
        }

        $code->code=$request->input('code');
        $code->pseudo=$request->input('pseudo');
        $code->description=$request->input('description');

        if(Auth::user() instanceof Admin)
            if($request->input('hotesse_id') != -1)
                $code->hotesse_id=$request->input('hotesse_id');


        $code->annonce_id=$request->input('annonce_id');

        $code->save();

        $photos=PhotoCode::where('code','=',null)->orWhere('code','=',$code->code)->get();

        foreach ($photos as $photo)
        {
                $photo->code=($request->input("photo".$photo->id)!=null)?$id:null;
                $photo->save();
        }
        return redirect()->route('code')->with("message",$message);
    }

    public function activeCode($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $code=Code::find($id);

        $this->activeCodePv($code,!$code->active);
        return redirect()->route('code');
    }

    public function activeAllCode($idHotesse)
    {
        $codes = Code::where("hotesse_id","=",$idHotesse)->get();
        foreach ($codes as $code)
            $this->activeCodePv($code,true);

        return back();
    }

    public function desactiveAllCode($idHotesse)
    {
        $codes = Code::where("hotesse_id","=",$idHotesse)->get();
        foreach ($codes as $code)
            $this->activeCodePv($code,false);

        return back();
    }

    private function activeCodePv(Code $code,$active)
    {
        $code->active =$active;
        $code->save();
    }


    public function deleteCode($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        try {
            Code::find($id)->delete();
            return redirect()->route('code');
        }
        catch (QueryException $e)
        {
            $bag = new MessageBag();
            $bag->add("err","une erreur s'est produite pendant la suppression");

            return redirect()->route("getUpdateCode",["id"=>$id])->withErrors($bag);
        }
    }


    public function APIget($cle,$id)
    {
        if(API::where('cle',"=", $cle)->count() == 0)
            return response()->json(['error' => 'Not authorized.'],403);

        $code = Code::find($id);
        if($code==null)
            return response()->json(null);

        $listePhoto = array();
        foreach ($code->photo as $photo)
        {
            $url=url(elixir("assets/images/users/".$photo->file.".jpg"));
            array_push($listePhoto,$url);
        }

        return response()->json(["code"=>$code->code,"description"=>$code->description,"statut"=>$code->dispo,"active"=>$code->active,"photo"=>$listePhoto]);
    }
}