<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\API;
use App\Hotesse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $admin=Admin::where('name',$request->input('name'))->where('password',hash('sha512',$request->input('password')))->first();

        if($admin != null && $admin->active)
        {
            Auth::shouldUse("web_admin");
            Auth::login($admin);
            $admin=Admin::find(Auth::id());
            $admin->co=1;
            $admin->derniere_connection=date_create();
            $admin->save();
            return redirect()->route("activite");
        }

        $hotesse= Hotesse::where('name',$request->input('name'))->where('password',hash('sha512',$request->input('password')))->first();
        if($hotesse!=null && $hotesse->active)
        {
            Auth::shouldUse("web");
            Auth::login($hotesse);
            $hotesse=Hotesse::find(Auth::id());
            $hotesse->co=1;
            $hotesse->derniere_connection=date_create();
            $hotesse->save();
            return redirect()->route("home");
        }

        $bag = new MessageBag();
        $bag->add("name","login/mot de passe incorrect");
        return back()->withInput(["name"=>$request->input('name')])->withErrors($bag);
    }

    public function logout(Request $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(Auth::guard('web')->check())
        {
            $hotesse=Hotesse::find(Auth::id());
            $hotesse->co=0;
            $hotesse->save();
        }
        Auth::logout();
        return redirect()->route("login");
    }

    public function resetPassword(Request $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");
    }

}
