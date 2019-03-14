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
Route::get('/register','RegisterController@showRegister')->name('showregister');
Route::get('/login','LoginController@showLogin')->name('showlogin');
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
Route::get('/edit-event-{id}',[
    'uses' => 'EventFormsController@showedit',
    'as' => 'showedit',
    'middleware' =>  'roles',
    'roles' => ['Admin']
        ]);
Route::post('/edit-event-{id}/record','EventFormsController@edit')->name('edit');
Route::delete('/delete-event-{id}','EventFormsController@delete')->name('delete');
Route::get('/saved-events-{page}',[
    'uses' => 'EventFormsController@showsavedevents',
    'as' => 'showsavedevents',
    'middleware' =>  'roles',
    'roles' => ['Admin']
        ]);
Route::post('/saved-events/edit-{id}/record','EventFormsController@showeditsave')->name('editsave');
// Rezervāciju pārvalde
Route::get('event-reservation-{id}','ReservationController@showreservationcreate')->name('showreservationcreate');
Route::post('event-reservation-{id}/result','ReservationController@reservationcreate')->name('reservationcreate');



