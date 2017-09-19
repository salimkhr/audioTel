<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
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

    public function admin()
    {
        return view('admin')->with("admins",Admin::all());
    }

    public function getFormAdmin($id=null)
    {
            return isset($id) ? view('admin.new')->with("admin", Admin::find($id)) : view('admin.new')->with("admin", new Admin());
    }

    public function postFormAdmin(AdminRequest $request,$id=null)
    {
        if(isset($id))
            $admin = Admin::find($id);
        else
        {
            $admin = new Admin;
            $admin->password=hash("sha512",$request->input('password'));
        }

        $admin->name=$request->input('name');
        $admin->role=$request->input('role');
        $admin->save();

        return redirect()->route('admin');
    }

    public function activeAdmin($id)
    {
        $admin=Admin::find($id);
        $admin->active =! $admin->active;
        $admin->save();
        return redirect()->route('hotesse');
    }

    public function deleteAdmin($id)
    {
        Admin::find($id)->delete();
        return redirect()->route('hotesse');
    }
}