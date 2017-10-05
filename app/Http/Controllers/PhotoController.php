<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\PhotoRequest;
use App\PhotoCode;
use App\PhotoHotesse;
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


    public function postFormPhotoCode(PhotoRequest $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/code', $imageName);

        $photo = new PhotoCode();
        $photo->file=$imageName;
        $photo->save();

        return back()->withInput();
    }

    public function postFormPhotoHotesse(PhotoRequest $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/hotesse', $imageName);

        $photo = new PhotoHotesse();
        $photo->file=$imageName;
        $photo->hotesse_id=Auth::id();
        $photo->save();

        return back()->withInput();
    }

}