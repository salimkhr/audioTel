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

Route::get('/hotesse/page/{page?}/{idAdmin?}/{search?}', 'HotesseController@index')->name('hotesse');
Route::get('/hotesse/{id}/{debut?}/{fin?}', 'HotesseController@hotesse')->name('getHotesse');
Route::get('/hotesse/update/{id}', 'HotesseController@getFormHotesse')->name('getUpdateHotesse');
Route::post('/hotesse/update/{id}', 'HotesseController@postFormHotesse')->name('postUpdateHotesse');
Route::get('/hotesse/new', 'HotesseController@getFormHotesse')->name('getNewHotesse');
Route::post('/hotesse/new', 'HotesseController@postFormHotesse')->name('postNewHotesse');
Route::get('/hotesse/active/{id}', 'HotesseController@activeHotesse')->name('activeHotesse');
Route::get('/hotesse/delete/{id}', 'HotesseController@deleteHotesse')->name('deleteHotesse');
Route::get('/hotesse/reporting/{id}/{debut?}/{fin?}', 'HotesseController@hotesseAdmin')->name('hotesseAdmin');
Route::get('/hotesse/{id}/code/page/{page?}', 'HotesseController@codeHotesse')->name('codeHotesse');


Route::get('/code/page/{page?}/{idAdmin?}/{search?}', 'CodeController@index')->name('code');
Route::get('/code/reporting/{code}/{debut?}/{fin?}/', 'CodeController@reporting')->name('reportingCode');
Route::get('/code/{id}', 'CodeController@code')->name('getCode');
Route::get('/code/new', 'CodeController@getFormCode')->name('getNewCode');
Route::post('/code/new','CodeController@postFormCode')->name('postNewCode');
Route::get('/code/reporting/{id}', 'CodeController@reportingCode')->name('getUpdateCode');
Route::get('/code/update/{id}', 'CodeController@getFormCode')->name('getUpdateCode');
Route::post('/code/update/{id}','CodeController@postFormCode')->name('postUpdateCode');
Route::get('/code/active/{id}','CodeController@activeCode')->name('activeCode');
Route::get('/code/bock/{id}','CodeController@bockCode')->name('bockCode');
Route::get('/code/active/all/{idHottesse}','CodeController@activeAllCode')->name('activeAllCode');
Route::get('/code/desactive/all/{idHottesse}','CodeController@desactiveAllCode')->name('desactiveAllCode');
Route::get('/code/delete/{id}','CodeController@deleteCode')->name('deleteCode');

Route::get('/annonce/','AnnonceController@index')->name('annonce');
Route::post('/annonce/update/{id}','AnnonceController@update')->name('postUpdateAnnonce');
Route::get('/annonce/delete/{id}','AnnonceController@delete')->name('deleteAnnonce');

Route::get('/client/', 'ClientController@client')->name('client');
Route::get('/client/new/', 'ClientController@getFormClient')->name('getNewClient');
Route::post('/client/new/', 'ClientController@postFormClient')->name('postNewClient');
Route::post('/client/update/{id}', 'ClientController@postFormClient')->name('postUpdateClient');
Route::get('/client/active/{id}','ClientController@activeClient')->name('activeClient');
Route::get('/client/{id}/', 'ClientController@getClient')->name('getClient');
Route::get('/client/delete/{id}','ClientController@delete')->name('deleteClient');

Route::get('/admin/', 'AdminController@admin')->name('admin');
Route::get('/admin/new', 'AdminController@getFormAdmin')->name('getNewAdmin');
Route::post('/admin/new', 'AdminController@postFormAdmin')->name('postNewAdmin');
Route::get('/admin/upadte/{id}', 'AdminController@getFormAdmin')->name('getUpdateAdmin');
Route::post('/admin/upadte/{id}', 'AdminController@postFormAdmin')->name('postUpdateAdmin');
Route::get('/admin/active/{id}','AdminController@activeAdmin')->name('activeAdmin');
Route::get('/admin/delete/{id}','AdminController@deleteAdmin')->name('deleteAdmin');
Route::get('/admin/reporting/{id}/{debut?}/{fin?}', 'AdminController@reporting')->name('reportingAdmin');
Route::get('/activite/', 'AdminController@activite')->name('activite');

Route::get('/api/', 'APIController@index')->name('api');
Route::get('/api/new', 'APIController@getFormAPI')->name('getNewAPI');
Route::get('/api/regenere/{id}', 'APIController@regenereAPI')->name('regenereAPI');
Route::post('/api/new', 'APIController@postFormAPI')->name('postNewAPI');
Route::get('/api/upadte/{ida}', 'APIController@getFormAPI')->name('getUpdateAPI');
Route::post('/api/upadte/{ida}', 'APIController@postFormAPI')->name('postUpdateAPI');
Route::get('/api/active/{ida}','APIController@activeAPI')->name('activeAPI');
Route::get('/api/delete/{ida}','APIController@deleteAPI')->name('deleteAPI');

Route::post('/photo/add/code', 'PhotoController@postFormPhotoCode')->name('postNewPhotoCode');
Route::post('/photo/add/hotesse/{id?}', 'PhotoController@postFormPhotoHotesse')->name('postNewPhotoHotesse');
Route::post('/photo/add/admin', 'PhotoController@postFormPhotoAdmin')->name('postNewPhotoAdmin');
Route::get('/photo/delete/admin/{id}', 'PhotoController@deleteFormPhotoAdmin')->name('deletePhotoAdmin');
Route::get('/photo/delete/code/{id}', 'PhotoController@deleteFormPhotoCode')->name('deletePhotoCode');
Route::get('/photo/delete/hotesse/{id}', 'PhotoController@deleteFormPhotoHotesse')->name('deletePhotoHotesse');

Route::get('/message/page/{page?}', 'MessageController@index')->name('message');
Route::get('/message/{id}', 'MessageController@get')->name('getMessage');
Route::get('/message/new/{appel?}', 'MessageController@new')->name('newMessage');
Route::post('/message/new', 'MessageController@send')->name('sendMessage');
Route::get('/logout/', 'Auth\LoginController@logout')->name('logout');
Route::post('/resetPassword/', 'Auth\LoginController@resetPassword')->name('logout');

Route::get('reporting/admin/{debut?}/{fin?}','HomeController@index')->name('home');
Route::post('updatePassword/','Auth\LoginController@updatePassword')->name('UpdatePassword');

Auth::routes();

Route::get('/', function() {
    return redirect()->route("home");
});
