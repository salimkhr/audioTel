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

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/hotesse', 'HotesseController@index')->name('hotesse');

Route::get('/admin', 'AdminController@index')->name('admin');

Route::get('/admin/hotesse', 'HotesseController@hotesse')->name('hotesseAdmin');
Route::get('/admin/hotesse/new', 'HotesseController@getFormHotesse')->name('getNewHotesse');
Route::post('/admin/hotesse/new','HotesseController@postFormHotesse')->name('postNewHotesse');
Route::get('/admin/hotesse/update/{id}', 'HotesseController@getFormHotesse')->name('getUpdateHotesse');
Route::post('/admin/hotesse/update/{id}','HotesseController@postFormHotesse')->name('postUpdateHotesse');
Route::get('/admin/hotesse/delete/{id}','HotesseController@deleteHotesse')->name('deleteHotesse');
Route::get('/admin/hotesse/active/{id}','HotesseController@activeHotesse')->name('activeHotesse');


Route::get('/admin/code', 'CodeController@code')->name('codeAdmin');
Route::get('/admin/code/new', 'CodeController@getFormCode')->name('getNewCode');
Route::post('/admin/code/new','CodeController@postFormCode')->name('postNewCode');
Route::get('/admin/code/update/{id}', 'CodeController@getFormCode')->name('getUpdateCode');
Route::post('/admin/code/update/{id}','CodeController@postFormCode')->name('postUpdateCode');
Route::get('/admin/code/active/{id}','CodeController@activeCode')->name('activeCode');
Route::get('/admin/code/delete/{id}','CodeController@deleteCode')->name('deleteCode');

Route::get('/admin/client', 'ClientController@client')->name('clientAdmin');
Route::get('/admin/admin', 'AdminController@admin')->name('adminAdmin');

Route::post('/logout/', 'Auth\LoginController@logout')->name('logout');
Route::get('/logoutAdmin/', 'Auth\LoginAdminController@logout')->name('logoutAdmin');

Route::get('/loginAdmin/', 'Auth\LoginAdminController@showLoginForm')->name('FormloginAdmin');
Route::post('/loginAdmin/', 'Auth\LoginAdminController@login')->name('loginAdmin');
Auth::routes();
