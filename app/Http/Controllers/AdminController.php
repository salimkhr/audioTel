<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Appel;
use App\Hotesse;
use App\Http\Requests\AdminRequest;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Mockery\Exception;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function admin()
    {
        if(Auth::user() instanceof Hotesse)
            abort(403,"acces admin");
        if(!$this->testLogin())
            return redirect()->route("login");

        return view('admin')->with("admins",Admin::all());
    }

    public function reporting($id,$debut=null,$fin=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse && Auth::id() != $id)
            abort(403, 'Unauthorized action.');

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
        $appels=Appel::where("admin_id","=",$id)->where('debut',">=",$dateDebut->format('Y-m-d'))->where("debut","<=",$dateFin->format('Y-m-d'))->get();

        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appels as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            if(isset($appel->tarif->prixMinute))
                $ca+=$duree*$appel->tarif->prixMinute;
        }

        return view('admin.report')->with("hotesses",Hotesse::all())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("admin",Admin::find($id))
            ->with("debut",$debut)
            ->with("fin",$fin);
    }

    public function activite($id,$debut=null,$fin=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse && Auth::id() != $id)
            abort(403, 'Unauthorized action.');

        $today= new DateTime();
        $appels=Appel::where("admin_id","=",Auth::id())->orderByDesc("debut")->limit(50)->get();
        $appelsToday=Appel::where("admin_id","=",Auth::id())->where('debut',">=",$today->format('Y-m-d'))->get();
        $HotesseCo=Hotesse::where("admin_id","=",Auth::id())->where("co","=","1");

        $dureeAppel=0;
        $nbAppel=0;
        $ca=0;
        foreach($appelsToday as $appel)
        {
            $duree=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $dureeAppel+=$duree;
            $nbAppel++;
            if(isset($appel->tarif->prixMinute))
                $ca+=$duree*$appel->tarif->prixMinute;
        }
        return view('activite')
            ->with("hotesses",$HotesseCo->get())
            ->with("nbHotesseCo",$HotesseCo->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("ca",$ca)
            ->with("appels",$appels)
            ->with("debut",$debut)
            ->with("fin",$fin);
    }


    public function getFormAdmin($id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        return isset($id) ? view('admin.new')->with("admin", Admin::find($id))->with("photos",Admin::find($id)->photos) : view('admin.new')->with("admin", new Admin())->with("photos",Auth::user()->photos);
    }

    public function postFormAdmin(AdminRequest $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
        {
            $message="ajout effectué avec succès";
            $admin = Admin::find($id);
        }

        else
        {
            $admin = new Admin;
            $admin->password=hash("sha512",$request->input('password'));
            $message="modification effectué avec succès";
        }

        $admin->name=$request->input('name');
        if($request->input('role')!=null)
            $admin->role=$request->input('role');

        $admin->photoAdmin_id=$request->input('photo_id');
        $admin->email=$request->input('email');

        $admin->save();

        return redirect()->route("admin")->with("message",$message);
    }

    public function activeAdmin($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $admin=Admin::find($id);
        $admin->active =! $admin->active;
        $admin->save();
        return redirect()->route('admin');
    }

    public function deleteAdmin($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        try {
            Admin::find($id)->delete();
            return redirect()->route('admin');
        }
        catch (QueryException $e)
        {
            $bag = new MessageBag();
            $bag->add("err","une erreur s'est produite pendant la suppression");

            return redirect()->route("getUpdateAdmin",["id"=>$id])->withErrors($bag);
        }

    }
}