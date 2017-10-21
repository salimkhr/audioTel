<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Hotesse;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\PhotoRequest;
use App\PhotoAdmin;
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


    public function postFormPhotoHotesse(PhotoRequest $request,$id=null)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/', $imageName);

        $photo = new PhotoHotesse();
        $photo->file=$imageName;
        if(Auth::user() instanceof Hotesse)
            $photo->hotesse_id=Auth::id();
        else
            if($id!=null)
                $photo->hotesse_id=$id;
            else
                $photo->admin_id=Auth::id();
        $photo->save();

        return back()->withInput()->with("message","photo ajoutée");
    }

    public function postFormPhotoAdmin(PhotoRequest $request)
    {
        if(!$this->testLoginAdmin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/', $imageName);

        $photo = new PhotoAdmin();
        $photo->file=$imageName;
        $photo->admin_id=Auth::id();
        $photo->save();

        return back()->withInput()->with("message","photo ajoutée");
    }
    public function deleteFormPhotoHotesse($id)
    {
        PhotoHotesse::find($id)->delete();
        return back()->withInput();
    }
}