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

Route::get('/hotesse', 'HotesseController@hotesse')->name('hotesse');
Route::get('/hotesse/update/{id}', 'HotesseController@getFormHotesse')->name('getUpdateHotesse');
Route::post('/hotesse/update/{id}', 'HotesseController@postFormHotesse')->name('postUpdateHotesse');
Route::get('/hotesse/new', 'HotesseController@getFormHotesse')->name('getNewHotesse');
Route::post('/hotesse/new', 'HotesseController@postFormHotesse')->name('postNewHotesse');
Route::post('/hotesse/active/{id}', 'HotesseController@activeHotesse')->name('activeHotesse');
Route::get('/hotesse/delete/{id}', 'HotesseController@deleteHotesse')->name('deleteHotesse');

Route::get('/code', 'CodeController@code')->name('code');
Route::get('/code/new', 'CodeController@getFormCode')->name('getNewCode');
Route::post('/admin/code/new','CodeController@postFormCode')->name('postNewCode');
Route::get('/code/update/{id}', 'CodeController@getFormCode')->name('getUpdateCode');
Route::post('/code/update/{id}','CodeController@postFormCode')->name('postUpdateCode');
Route::get('/code/active/{id}','CodeController@activeCode')->name('activeCode');
Route::get('/code/delete/{id}','CodeController@deleteCode')->name('deleteCode');

Route::get('/client', 'ClientController@client')->name('client');

Route::get('/admin', 'AdminController@admin')->name('admin');
Route::get('/admin/new', 'AdminController@getFormAdmin')->name('getNewAdmin');
Route::post('/admin/new', 'AdminController@postFormAdmin')->name('postNewAdmin');
Route::get('/admin/active/{id}','AdminController@activeAdmin')->name('activeAdmin');
Route::get('/admin/delete/{id}','AdminController@activeAdmin')->name('deleteAdmin');

Route::get('/logout/', 'Auth\LoginController@logout')->name('logout');
Route::get('/logoutAdmin/', 'Auth\LoginAdminController@logout')->name('logoutAdmin');

Route::get('/','HomeController@index')->name('home');
Auth::routes();
