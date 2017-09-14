<?php

namespace App\Http\Controllers;

use App\Hotesse;
use App\Appel;
use App\Http\Requests\HotesseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotesseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hotesse.index')->with("hotesses",Hotesse::all())->with("appels",Appel::where("hotesse_id","=",Auth::guard('web')->id())->get());
    }

    public function hotesse()
    {
        $hotesses=Hotesse::all();
        return view('admin.hotesse')->with("hotesses",$hotesses);
    }

    public function getFormHotesse($id=null)
    {
        if(isset($id))
            return view('admin.newHotesse')->with("hotesse",Hotesse::find($id));
        else
            return view('admin.newHotesse')->with("hotesse",new Hotesse());
    }

    public function postFormHotesse(HotesseRequest $request,$id=null)
    {
        if(isset($id))
            $hotesse = Hotesse::find($id);
        else
            $hotesse = new Hotesse;

        $hotesse->name=$request->input('name');
        $hotesse->tel=$request->input('tel');
        $hotesse->admin_id=1;
        $hotesse->save();

        return redirect()->route('hotesseAdmin');
    }

    public function activeHotesse($id)
    {
        $hotesse=Hotesse::find($id);
        $hotesse->active =! $hotesse->active;
        $hotesse->save();
        return redirect()->route('hotesseAdmin');
    }

    public function deleteHotesse($id)
    {
        Hotesse::find($id)->delete();
        return redirect()->route('hotesseAdmin');
    }
}