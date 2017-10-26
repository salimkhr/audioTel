<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("/hotesses",'HotesseController@APIindex');
Route::get("/hotesse/{id}",'HotesseController@APIget');
Route::get("/code/{id}",'CodeController@APIget');
Route::get("/clients",'ClientController@APIindex');
Route::get("/client/{id}",'ClientController@APIget');
Route::get("/client/{id}/credit",'ClientController@APIgetCredit');
Route::post("/client/{id}/credit",'ClientController@APIpostCredit');
Route::get("/client/{id}/call",'ClientController@APIcall');
Route::post("/client/create",'ClientController@APIpost');

