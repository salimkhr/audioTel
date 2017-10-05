<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
Route::pattern('id', '[0-9]+');

Route::get('/hotesse/', 'HotesseController@index')->name('hotesse');
Route::get('/hotesse/{id}', 'HotesseController@hotesse')->name('getHotesse');
Route::get('/hotesse/update/{id}', 'HotesseController@getFormHotesse')->name('getUpdateHotesse');
Route::post('/hotesse/update/{id}', 'HotesseController@postFormHotesse')->name('postUpdateHotesse');
Route::get('/hotesse/new', 'HotesseController@getFormHotesse')->name('getNewHotesse');
Route::post('/hotesse/new', 'HotesseController@postFormHotesse')->name('postNewHotesse');
Route::get('/hotesse/active/{id}', 'HotesseController@activeHotesse')->name('activeHotesse');
Route::get('/hotesse/delete/{id}', 'HotesseController@deleteHotesse')->name('deleteHotesse');
Route::get('/hotesse/{id}/code', 'HotesseController@codeHotesse')->name('codeHotesse');


Route::get('/code/', 'CodeController@index')->name('code');
Route::get('/code/{id}', 'CodeController@code')->name('getCode');
Route::get('/code/new', 'CodeController@getFormCode')->name('getNewCode');
Route::post('/code/new','CodeController@postFormCode')->name('postNewCode');
Route::get('/code/update/{id}', 'CodeController@getFormCode')->name('getUpdateCode');
Route::post('/code/update/{id}','CodeController@postFormCode')->name('postUpdateCode');
Route::get('/code/active/{id}','CodeController@activeCode')->name('activeCode');
Route::get('/code/active/all/{idHottesse}','CodeController@activeAllCode')->name('activeAllCode');
Route::get('/code/desactive/all/{idHottesse}','CodeController@desactiveAllCode')->name('desactiveAllCode');

Route::get('/code/delete/{id}','CodeController@deleteCode')->name('deleteCode');

Route::get('/client/', 'ClientController@client')->name('client');
Route::get('/client/new/', 'ClientController@getFormClient')->name('getNewClient');
Route::post('/client/new/', 'ClientController@postFormClient')->name('postNewClient');

Route::get('/admin/', 'AdminController@admin')->name('admin');
Route::get('/admin/new', 'AdminController@getFormAdmin')->name('getNewAdmin');
Route::post('/admin/new', 'AdminController@postFormAdmin')->name('postNewAdmin');
Route::get('/admin/upadte/{id}', 'AdminController@getFormAdmin')->name('getUpdateAdmin');
Route::post('/admin/upadte/{id}', 'AdminController@postFormAdmin')->name('postUpdateAdmin');
Route::get('/admin/active/{id}','AdminController@activeAdmin')->name('activeAdmin');
Route::get('/admin/delete/{id}','AdminController@deleteAdmin')->name('deleteAdmin');

Route::get('/api/', 'APIController@index')->name('api');
Route::get('/api/new', 'APIController@getFormAPI')->name('getNewAPI');
Route::post('/api/new', 'APIController@postFormAPI')->name('postNewAPI');
Route::get('/api/upadte/{id}', 'APIController@getFormAPI')->name('getUpdateAPI');
Route::post('/api/upadte/{id}', 'APIController@postFormAPI')->name('postUpdateAPI');
Route::get('/api/active/{id}','APIController@activeAPI')->name('activeAPI');
Route::get('/api/delete/{id}','APIController@activeAPI')->name('deleteAPI');

Route::post('/photo/add/code', 'PhotoController@postFormPhotoCode')->name('postNewPhotoCode');
Route::post('/photo/add/hotesse', 'PhotoController@postFormPhotoHotesse')->name('postNewPhotoHotesse');

Route::get('/logout/', 'Auth\LoginController@logout')->name('logout');

Route::get('/','HomeController@index')->name('home');
Auth::routes();

Route::get("/{cle}/api/hotesses",'HotesseController@APIindex');
Route::get("/{cle}/api/hotesse/{id}",'HotesseController@APIget');
Route::get("/{cle}/api/code/{id}",'CodeController@APIget');
Route::get("/{cle}/api/clients",'ClientController@APIindex');
Route::get("/{cle}/api/client/{id}",'ClientController@APIget');
Route::post("/{cle}/api/client/{id}",'ClientController@APIpost');
