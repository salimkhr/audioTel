<?php

namespace App\Http\Controllers;

use App\AccesAPI;
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
        if(!$this->testLoginAdmin())
            return redirect()->route("login");

        $api=API::where("admin_id","=",Auth::id())->get();

        foreach ($api as $apic)
        {
            $acces =AccesAPI::where("id_API","=",$apic->id)->first();
            if($acces != null)
                $apic->dateUse=date_format(date_create($acces->created_at), 'd/m/Y H:i:s');
            else
                $apic->dateUse="";
        }

        return view('api')->with("apis",$api);
    }


    public function getFormAPI($id=null)
    {
        if (!$this->testLogin())
            return redirect()->route("login");


        if (isset($id))
            return view('api.new')->with("api", API::find($id))->with("listAcces", AccesAPI::where("id_API", "=", $id)->get());
        else {
            $api = new API();
            $api->id = $this->RandomString(20);
            $api->cle = $this->RandomString(20);
            $api->admin_id = Auth::id();
            $api->save();
            return redirect()->route('api');
        }
    }

    public function postFormAPI(Request $request,$id)
    {
        $api = API::find($id);
        $api->name = $request->input("name");
        $api->save();
        return redirect()->back()->with("message","modification effectué");
    }


    public function activeAPI($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $api=API::find($id);
        $api->active =! $api->active;
        $api->save();
        return redirect()->back()->with("message","l'API a été ".( $api->active?"activé":"désactivé"));
    }

    public function regenereAPI($id)
    {
        if(!$this->testLoginAdmin())
            return redirect()->route("login");

        $api=API::find($id);
        $api->cle = $this->RandomString(30);
        $api->save();
        return redirect()->back()->with("message","cle regéneré");
    }

    public function deleteAPI($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        API::find($id)->delete();
        return redirect()->route("api")->with("message","API supprimé");
    }
}