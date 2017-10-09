<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Hotesse;
use App\Http\Requests\AdminRequest;
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

    public function getFormAdmin($id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        return isset($id) ? view('admin.new')->with("admin", Admin::find($id)) : view('admin.new')->with("admin", new Admin());
    }

    public function postFormAdmin(AdminRequest $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        if(isset($id))
            $admin = Admin::find($id);
        else
        {
            $admin = new Admin;
            $admin->password=hash("sha512",$request->input('password'));
        }

        $admin->name=$request->input('name');
        if($request->input('role')!=null)
            $admin->role=$request->input('role');

        $admin->photoAdmin_id=$request->input('photo_id');
        $admin->email=$request->input('email');

        $admin->save();

        return redirect()->back();
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