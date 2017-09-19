<?php

namespace App\Http\Controllers;

use App\API;
use App\Hotesse;
use App\Appel;
use App\Http\Requests\APIRequest;
use App\Http\Requests\HotesseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIController extends Controller
{

    public function index()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $api=API::all();
        return view('api')->with("apis",$api);
    }

    public function hotesse($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $appelToday = Appel::where("hotesse_id","=",$id)->whereDate('debut',date("Y-m-d"))->get();
        $appels=Appel::where("hotesse_id","=",$id)->get();

        $dureeAppel=0;
        $nbAppel=0;
        foreach($appelToday as $appel)
        {
            $dureeAppel+=date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i');
            $nbAppel++;
        }

        return view('index')->with("hotesses",Hotesse::all())
            ->with("nbHotesseCo",Hotesse::where("co","<>","0")->count())
            ->with("dureeAppel",$dureeAppel)
            ->with("nbAppel",$nbAppel)
            ->with("appels",$appels);
    }

    public function getFormAPI($id=null)
    {
        
        if(isset($id))
            return view('api.new')->with("api",API::find($id));
        else
            return view('api.new')->with("api",new API());
    }

    public function postFormAPI(APIRequest $request,$id=null)
    {
        dump("azerty");
        if(isset($id))
            $api = API::find($id);
        else
        {
            $api = new API();
        }

        $api->cle=$request->input('cle');
        $api->save();
        dump($api);
        return redirect()->route('api');
    }

    public function activeAPI($id)
    {
        
        $api=API::find($id);
        dump($api);
        $api->active =! $api->active;
        $api->save();
        return redirect()->route('api');
    }

    public function deleteAPI($id)
    {
        
        API::find($id)->delete();
        return redirect()->route('api');
    }
}