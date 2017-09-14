<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Appel;
use App\Hotesse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index')->with("hotesses",Hotesse::all())->with("appels",Appel::where("admin_id","=",Auth::guard('web_admin')->id())->get());
    }


    public function admin()
    {
       return view('admin.admin')->with("admins",Admin::all());
    }
}