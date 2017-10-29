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

    public function newMessage($appel = null)
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
            if(isset($tel->appellant) && $tel->appellant != "ANONYME" )
                $listTel[$tel->id]= substr($tel->appellant,0,strlen($tel->appellant)-5).'*****';
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
        if(!$this->testLogin())
            return redirect()->route("login");

        $message = new Message();
        $appel=Appel::find($request->input("tel"));

        if(substr($appel->appellant,0,1)!="+")
            if(substr($appel->appellant,0,1)=="0")
                switch($appel->pays)
                {
                    case "FR":$message->tel= "+33".substr($appel->appellant,1);break;
                    case "BE":$message->tel= "+32".substr($appel->appellant,1);break;
                    case "CH":$message->tel= "+41".substr($appel->appellant,1);break;
                }
            else
                $message->tel="+".$appel->appellant;
        else
            $message->tel=$appel->appellant;


        $message->contenu = $request->input("contenu");

        if($message->contenu == null)
            $message->contenu = "";

        $message->hotesse_id = Auth::id();
        $message->save();

        if($request->input("photo_id")!=null || $request->input("annonce_id")!=null)
        {
            $url = 'https://api.smsglobal.com/mms/sendmms.php';
            $fields = array("username"=>"apercu","password"=>"vj408XKz","number"=>$message->tel,"message"=>$message->contenu,);

            $i=0;
            if($request->input("photo_id")!=null)
            {
                $photo=PhotoHotesse::find($request->input("photo_id"));
                $path=url(elixir('/images/catalog/'.$photo->file));
                $fields["attachment0"]=base64_encode(file_get_contents($path));
                $fields["type0"]="image/".pathinfo($path, PATHINFO_EXTENSION);
                $fields["content_name0"]=$photo->file;

                $message->photoHotesse_id = $request->input("photo_id");
                $message->save();

                $i++;
            }
            if($request->input("annonce_id")!=null)
            {
                $annonce=Annonce::find($request->input("annonce_id"));
                $path=url(elixir('audio/annonce/'.$annonce->file.'.mp3'));
                $fields["attachment".$i]=base64_encode(file_get_contents($path));
                $fields["type".$i]="audio/mpeg";
                $fields["content_name".$i]=$annonce->file.".mp3";

                $message->annonce_id = $request->input("annonce_id");
                $message->save();
            }

            //open connection
            $ch = curl_init($url);

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($fields));
            //curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
            if($result)
                return redirect()->route("message")->with("message","message: '".$message->contenu."' envoyé");
            else
                return redirect()->route("message")->with("message","une erreur c'est produite");

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
