<?php

namespace App\Http\Controllers;

use App\Message;
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

        $messages=Message::where("hotesse_id","=",Auth::id())->get();
        $clients=[];

        foreach ($messages as $message)
        {
            $code =$message->client->tel;

            if(isset($clients[$code]))
                $clients[$code]++;
            else
                $clients[$code]=1;
        }
        return view("message")->with("clients",$clients)->with("messages",$messages);
    }

    public function get($id)
    {
        return view("message.message")->with("message",Message::find($id));

    }
}
