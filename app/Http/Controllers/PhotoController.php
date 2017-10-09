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


    public function postFormPhotoCode(PhotoRequest $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/code', $imageName);

        $photo = new PhotoCode();
        $photo->file=$imageName;
        $photo->save();

        return back()->withInput()->with("message","photo ajouté");
    }

    public function postFormPhotoHotesse(PhotoRequest $request)
    {
        if(!$this->testLogin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/hotesse', $imageName);

        $photo = new PhotoHotesse();
        $photo->file=$imageName;
        if(Auth::user() instanceof Hotesse)
            $photo->hotesse_id=Auth::id();
        else
            $photo->admin_id=Auth::id();
        $photo->save();

        return back()->withInput()->with("message","photo ajouté");
    }

    public function postFormPhotoAdmin(PhotoRequest $request)
    {
        if(!$this->testLoginAdmin())
            return redirect()->route("login");

        $imageName = $this->RandomString().'.' .$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/images/catalog/hotesse', $imageName);

        $photo = new PhotoAdmin();
        $photo->file=$imageName;
        $photo->admin_id=Auth::id();
        $photo->save();

        return back()->withInput()->with("message","photo ajouté");
    }
    public function deleteFormPhotoHotesse($id)
    {
        PhotoHotesse::find($id)->delete();
        return back()->withInput();
    }

    public function deleteFormPhotoCode($id)
    {
        PhotoCode::find($id)->delete();
        return back()->withInput();
    }

}