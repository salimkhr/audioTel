<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Hotesse;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index')->with("hotesses",Hotesse::all());
    }


    public function admin()
    {
       return view('admin.admin')->with("admins",Admin::all());
    }
}