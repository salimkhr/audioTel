<?php

namespace App\Http\Controllers;

use App\Hotesse;
use App\Appel;
use App\Http\Requests\HotesseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotesseController extends Controller
{
    public function __construct()
    {
        if(Auth::user() == null)
            Auth::shouldUse("web_admin");

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->login();
        return view('hotesse.index')->with("hotesses",Hotesse::all())->with("appels",Appel::where("hotesse_id","=",Auth::guard('web')->id())->get());
    }

    public function hotesse()
    {

        $this->login();
        $hotesses=Hotesse::all();
        return view('hotesse')->with("hotesses",$hotesses);
    }

    public function getFormHotesse($id=null)
    {
        $this->login();
        if(isset($id))
            return view('hotesse.new')->with("hotesse",Hotesse::find($id));
        else
            return view('hotesse.new')->with("hotesse",new Hotesse());
    }

    public function postFormHotesse(HotesseRequest $request,$id=null)
    {
        $this->login();
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
        $this->login();
        $hotesse=Hotesse::find($id);
        $hotesse->active =! $hotesse->active;
        $hotesse->save();
        return redirect()->route('hotesse');
    }

    public function deleteHotesse($id)
    {
        $this->login();
        Hotesse::find($id)->delete();
        return redirect()->route('hotesse');
    }
}