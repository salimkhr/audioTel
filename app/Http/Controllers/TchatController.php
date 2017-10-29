<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Hotesse;
use App\ReadMessage;
use App\Tchat;
use App\TchatG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TchatController extends Controller
{

    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */

    public function index()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {
            $tchats = Tchat::where("hotesse_id","=",Auth::id())->get();
            foreach($tchats as $tchat)
            {
                if($tchat->expediteur == "A")
                {
                    $tchat->read=true;
                    $tchat->save();
                }
            }
            return view('tchat')->with("tchats",$tchats);
        }
        else {
            $tchat = Tchat::where("admin_id", "=", Auth::id())->orderBy("created_at")->first();
            if($tchat != null)
                $idHotesse=$tchat->hotesse_id;
            else
            {
                $hotesse=Hotesse::where("admin_id", "=", Auth::id())->first();
                if($hotesse != null)
                    $idHotesse=$hotesse->id;
                else
                    return abort(401,"vous n'avez pas d'hotesse");
            }
            return redirect()->route("tchat.show",["id"=>$idHotesse]);
        }
    }

    public function general()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {
            $tchats = TchatG::all();
            foreach($tchats as $tchat)
            {
                if(ReadMessage::where("hotesse_id","=",Auth::id())->where("message_id","=",$tchat->id)->count() == 0)
                {
                   $rm = new ReadMessage();
                   $rm->message_id=$tchat->id;
                   $rm->hotesse_id=Auth::id();
                   $rm->save();
                }
            }
        }
        else {
            $tchats = TchatG::all();
            foreach($tchats as $tchat)
            {
                if(ReadMessage::where("admin_id","=",Auth::id())->where("message_id","=",$tchat->id)->count() == 0)
                {
                    $rm = new ReadMessage();
                    $rm->message_id=$tchat->id;
                    $rm->admin_id=Auth::id();
                    $rm->save();
                }
            }
        }
        return view('tchat')->with("tchats",$tchats);
    }

    public function generalpost(Request $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $tchatg = new TchatG();
        if(Auth::user() instanceof Admin)
            $tchatg->id_admin=Auth::id();
        else
            $tchatg->id_hotesse=Auth::id();

        $tchatg->message=$request->input("subject");

        $tchatg->save();
        return redirect()->back();
    }

    public function show($id)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $tchats = Tchat::where("hotesse_id","=",$id)->get();
        foreach($tchats as $tchat)
        {
            if($tchat->expediteur == "H")
            {
                $tchat->read=true;
                $tchat->save();
            }
        }
        return view('tchat')->with("tchats",$tchats)->with("id",$id);
    }

    public function store(Request $request,$idHotesse=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $tchat=new Tchat();
        $tchat->message=$request->input("subject");
        if(Auth::user() instanceof Hotesse)
        {
            $tchat->hotesse_id=Auth::id();
            $tchat->admin_id=Auth::user()->admin_id;
            $tchat->expediteur="H";
        }
        else
        {
            $tchat->hotesse_id=$idHotesse;
            $tchat->admin_id=Auth::id();
            $tchat->expediteur="A";
        }

        $tchat->save();

        return redirect()->back();
    }

    public function nbmessage()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Admin)
        {
            $reads = ReadMessage::where("admin_id","=",Auth::id())->get();
            $tabread=[];
            foreach($reads as $read)
            {
                array_push($tabread,$read->message_id);
            }
            $tchats = TchatG::wherenotin("id",$tabread)->count();
            return response()->json(Tchat::where("admin_id","=",Auth::id())->where("read","=","0")->where("expediteur","=","H")->count()+$tchats);

        }
        else
        {
            $reads = ReadMessage::where("hotesse_id","=",Auth::id())->get();
            $tabread=[];
            foreach($reads as $read)
            {
                array_push($tabread,$read->message_id);
            }

            $tchats = TchatG::wherenotin("id",$tabread)->count();
            return response()->json(Tchat::where("hotesse_id","=",Auth::id())->where("read","=","0")->where("expediteur","=","A")->count()+$tchats);
        }
    }

    public function updateMessage($hotesse_id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Admin)
        {
            $tchats=Tchat::where("admin_id","=",Auth::id())->where("read","=","0")->where("expediteur","=","H")->where("hotesse_id","=",$hotesse_id)->get();

            foreach($tchats as $tchat)
            {

                $tchat->read=true;
                $tchat->save();
                $tchat->date=date_format(date_create($tchat->created_at),'d/m/Y H:i:s');
                $tchat->name=$tchat->admin->name;
                $tchat->img=url(elixir("images/catalog/".$tchat->getAuteur()->photo->file));
            }

        }
        else {
            $tchats = Tchat::where("hotesse_id", "=", Auth::id())->where("read", "=", "0")->where("expediteur", "=", "A")->get();

            foreach($tchats as $tchat)
            {
                $tchat->read=true;
                $tchat->save();
                $tchat->date=date_format(date_create($tchat->created_at),'d/m/Y H:i:s');
                $tchat->name=$tchat->admin->name;
                $tchat->img=url(elixir("images/catalog/".$tchat->getAuteur()->photo->file));

            }
        }

        return response()->json($tchats);
    }

    public function updateMessageGeneral()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::user() instanceof Hotesse)
        {

            $reads = ReadMessage::where("hotesse_id","=",Auth::id())->get();
            $tabread=[];
            foreach($reads as $read)
            {
                array_push($tabread,$read->message_id);
            }

            $tchats = TchatG::wherenotin("id",$tabread)->get();
            foreach($tchats as $tchat)
            {
                $rm = new ReadMessage();
                $rm->message_id=$tchat->id;
                $rm->hotesse_id=Auth::id();
                $rm->save();
                $tchat->date=date_format(date_create($tchat->created_at),'d/m/Y H:i:s');
                $tchat->name=$tchat->getAuteur()->name;
                $tchat->img=url(elixir("images/catalog/".$tchat->getAuteur()->photo->file));
            }
        }
        else {
            $reads = ReadMessage::where("admin_id","=",Auth::id())->get();
            $tabread=[];
            foreach($reads as $read)
            {
                array_push($tabread,$read->message_id);
            }

            $tchats = TchatG::wherenotin("id",$tabread)->get();
            foreach($tchats as $tchat)
            {
                $rm = new ReadMessage();
                $rm->message_id=$tchat->id;
                $rm->admin_id=Auth::id();
                $rm->save();
                $tchat->date=date_format(date_create($tchat->created_at),'d/m/Y H:i:s');
                $tchat->name=$tchat->getAuteur()->name;
                $tchat->img=url(elixir("images/catalog/".$tchat->getAuteur()->photo->file));
            }
        }
        return response()->json($tchats);
    }

    public function delete($id)
    {
        $tchat=TchatG::find($id);
            if($tchat != null)
                $tchat->delete();
        return redirect()->back();
    }
}
