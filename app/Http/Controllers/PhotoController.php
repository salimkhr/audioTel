<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\PhotoRequest;
use App\Photo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Mockery\Exception;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function postFormPhoto(PhotoRequest $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        dump($request->file());
        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/', $imageName);

        $photo = new Photo();
        $photo->file=$imageName;
        $photo->save();

        return back()->withInput();
    }

}