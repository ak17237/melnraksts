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


Route::get('/','HomeController@index')->name('home');

Auth::routes();
// Autorizācija
Route::get('/register','RegisterController@showRegister')->name('showregister')->middleware('guest');
Route::get('/login','LoginController@showLogin')->name('showlogin')->middleware('guest');
Route::post('/login/check','LoginController@Login')->name('login');
Route::post('/register','RegisterController@Register')->name('register');
// Profila maiņa
Route::get('/profile',[
    'uses' => 'ProfileController@index',
    'as' => 'profile.index',
    'middleware' =>  'roles',
    'roles' => ['User','Admin']
        ]);
Route::post('/profile/changename','ProfileController@changename')->name('profile.changename');
Route::post('/profile/changesurname','ProfileController@changesurname')->name('profile.changesurname');
Route::post('/profile/changeemail','ProfileController@changeemail')->name('profile.changeemail');
Route::get('/profile/changepass',[
    'uses' => 'ProfileController@changepass',
    'as' => 'profile.changepass',
    'middleware' =>  'roles',
    'roles' => ['User','Admin']
        ]);
Route::post('/profile/changepassword','ProfileController@changepassword')->name('profile.changepassword');

// Pasākumu pārvaldes formas
Route::get('/create-event',[
    'uses' => 'EventFormsController@showcreate',
    'as' => 'showcreate',
    'middleware' =>  'roles',
    'roles' => ['Admin']
        ]);
Route::post('/create-event/results','EventFormsController@create')->name('create');
Route::get('/event-{id}/edit',[
    'uses' => 'EventFormsController@showedit',
    'as' => 'showedit',
    'middleware' =>  ['roles','author'],
    'roles' => ['Admin']
        ]);
Route::post('/event-{id}/edit/record','EventFormsController@edit')->name('edit');
Route::delete('event-{id}/delete','EventFormsController@delete')->name('delete');
Route::get('/saved-events-{page}',[
    'uses' => 'EventFormsController@showsavedevents',
    'as' => 'showsavedevents',
    'middleware' =>  'roles',
    'roles' => ['Admin']
        ]);
Route::get('/event-{id}','EventFormsController@showevent')->name('showevent');
// Rezervāciju pārvalde
Route::get('event-{id}/reservation',[
    'uses' => 'ReservationController@showreservationcreate',
    'as' => 'showreservationcreate',
    'middleware' =>  'roles',
    'roles' => ['User','Admin']
        ]);
Route::post('event-{id}/reservation/result','ReservationController@reservationcreate')->name('reservationcreate');



