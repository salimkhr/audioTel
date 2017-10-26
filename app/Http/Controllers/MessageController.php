<?php

namespace App\Http\Controllers;

use App\Annonce;
use App\Appel;
use App\Client;
use App\Code;
use App\Http\Requests\MessageRequest;
use App\Message;
use App\PhotoHotesse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    /**
     * Create a new controller instance.
     */

    public function index()
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $nbPages=Message::where("hotesse_id","=",Auth::id())->count();
        $messages=Message::where("hotesse_id","=",Auth::id())->orderByDesc("created_at")->get();
        $clients=[];

        if($nbPages %10 !=0)
        {
            $nbPages=($nbPages/10)+1;
        }
        else
        {
            $nbPages=($nbPages/10);
        }

        return view("message")->with("clients",$clients)->with("messages",$messages);
    }

    public function new($appel = null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if($appel == null)
            $tels = Appel::where("hotesse_id","=",Auth::id())->get();
        else
            $tels = array(Appel::find($appel));

        $listTel=[];
        $listCode=[];

        foreach ($tels as $tel)
        {
            if(isset($tel->appellant) && $tel->appellant != "ANONYME" ) $listTel[$tel->id]=substr($tel->appellant,0,4)."******";
        }

        $codes=Code::where("hotesse_id","=",Auth::id())->get();
        foreach ($codes as $code)
        {
            $listCode[$code->code]=$code->pseudo." (".$code->code.")";
        }

        return view("message.new")->with("tels",$listTel)->with("codes",$listCode)->with("photos",Auth::user()->photos)->with("annonces",Auth::user()->annonces);
    }

    public function send(Request $request)
    {
        $message = new Message();
        $appel=Appel::find($request->input("tel"));
        $message->tel = $appel->appellant;

        $message->contenu = $request->input("contenu");
        $message->hotesse_id = Auth::id();
        $message->save();

        if($request->input("photo_id")!=null || $request->input("annonce_id")!=null)
        {

        }
        else
        {
            try {

                $messageBird = new \MessageBird\Client('gFCS8VchOaRYsWeR0yf814KV0');
                $messageSend = new \MessageBird\Objects\Message();
                $messageSend->originator = $message->hotesse->name;
                $messageSend->recipients = array(substr($message->tel,1,strlen($message->tel)));
                $messageSend->body =$message->contenu;

                $messageBird->messages->create($messageSend);

                return redirect()->route("message")->with("message","message: '".$message->contenu."' envoyé");
            } catch (\MessageBird\Exceptions\AuthenticateException $e) {
                // Authentication failed. Is this a wrong access_key?
                echo $e->getMessage();

            } catch (\MessageBird\Exceptions\BalanceException $e) {
                // That means that you are out of credits. Only called on creation of a object.
                echo $e->getMessage();

            } catch (\Exception $e) {
                // Request failed. More information can be found in the body.

                // Echo's the error messages, split by a comma (,)
                echo $e->getMessage();
            }
        }
    }
}
