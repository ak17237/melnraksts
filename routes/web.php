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


Route::get('/','HomeController@index')->name('home'); // Galvenā lapa
Auth::routes();
// Autorizācija
Route::get('/register','RegisterController@showRegister')->name('showregister')->middleware('guest'); // Reģistrācijas lapa
Route::get('/login','LoginController@showLogin')->name('showlogin')->middleware('guest'); // Logina lapa
Route::post('/login/check','LoginController@Login')->name('login');
Route::post('/register','RegisterController@Register')->name('register');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout'); // Logout funkcija
// Profila maiņa
Route::get('/profile',[ // Profila lapa
    'uses' => 'ProfileController@index',
    'as' => 'profile.index',
    'middleware' =>  'roles',
    'roles' => ['User','Admin']
        ]);
Route::post('/profile/changename','ProfileController@changename')->name('profile.changename');
Route::post('/profile/changesurname','ProfileController@changesurname')->name('profile.changesurname');
Route::post('/profile/changeemail','ProfileController@changeemail')->name('profile.changeemail');
Route::get('/profile/changepass',[ // Paroles maiņas lapa
    'uses' => 'ProfileController@changepass',
    'as' => 'profile.changepass',
    'middleware' =>  'roles',
    'roles' => ['User','Admin']
        ]);
Route::post('/profile/changepassword','ProfileController@changepassword')->name('profile.changepassword');

// Pasākumu pārvaldes formas
Route::get('/create-event',[ // Pasākuma izveides lapa
    'uses' => 'EventFormsController@showcreate',
    'as' => 'showcreate',
    'middleware' =>  'roles',
    'roles' => ['Admin']
        ]);
Route::post('/create-event/results','EventFormsController@create')->name('create');
Route::get('/event/{id}/edit',[ // Pasākuma rediģēšanas lapa
    'uses' => 'EventFormsController@showedit',
    'as' => 'showedit',
    'middleware' =>  ['roles','author','existevent'],
    'roles' => ['Admin']
        ]);
Route::post('/event/{id}/edit/record','EventFormsController@edit')->name('edit')->middleware('existevent');
Route::delete('event/{id}/delete',[ // Pasākuma dzēšanas funkcija
    'uses' => 'EventFormsController@delete',
    'as' => 'delete',
    'middleware' =>  ['roles','existevent'],
    'roles' => ['Admin']
        ]);
Route::get('/saved-events-{page}',[ // Melnrakstu lapa
    'uses' => 'EventFormsController@showsavedevents',
    'as' => 'showsavedevents',
    'middleware' =>  'roles',
    'roles' => ['Admin']
        ]);
Route::get('/event/{id}/show','EventFormsController@showevent')->name('showevent')->middleware('saveevent')->middleware('existevent'); // Pasākuma apskates lapa
Route::get('/download/{pdfname}','EventFormsController@downloadpdf')->name('downloadpdf');
Route::post('event/{id}/edit/{filename}/delete','EventFormsController@deletefile')->name('deletefile');
Route::post('event/{id}/pdfdelete','EventFormsController@pdfdelete')->name('pdfdelete');

// Rezervāciju pārvalde
Route::get('event/{id}/{extension}/reservation',[ // Rezervācijas izveides lapa
    'uses' => 'ReservationController@showreservationcreate',
    'as' => 'showreservationcreate',
    'middleware' =>  ['roles','vipevent'],
    'roles' => ['User','Admin']
        ]);
Route::post('event/{id}/{extension}/reservation/result','ReservationController@reservationcreate')->name('reservationcreate')->middleware('vipevent');
Route::get('/myreservations-{page}',[ // Manas rezervācijas sadaļas lapa lietotājiem
    'uses' => 'ReservationController@showreservationusers',
    'as' => 'reservationusers',
    'middleware' =>  ['roles'],
    'roles' => ['User','Admin']
        ]);
Route::get('reservation/{id}/show',[ // Rezervācijas apskates lapa
    'uses' => 'ReservationController@showreservation',
    'as' => 'showreservation',
    'middleware' =>  ['roles','creator','existreserv'],
    'roles' => ['User','Admin']
        ]);
Route::get('reservation/{id}/edit',[ // Rezervācijas rediģēšanas lapa
    'uses' => 'ReservationController@showreservationedit',
    'as' => 'showreservationedit',
    'middleware' =>  ['roles','creator','existreserv','editable'],
    'roles' => ['User','Admin']
        ]);
Route::get('event/{id}/reservations',[ // Visas rezervācijas noteiktam pasākumam lapa adminiem
    'uses' => 'ReservationController@showreservationadmins',
    'as' => 'showreservationadmins',
    'middleware' =>  ['roles','existevent'],
    'roles' => ['Admin']
        ]);
Route::post('reservation/{id}/edit/result','ReservationController@reservationedit')->name('reservationedit');
Route::delete('reservation/{id}/delete',[ // Rezervācijas dzēšanas lapa
    'uses' => 'ReservationController@reservationdelete',
    'as' => 'reservationdelete',
    'middleware' =>  ['roles'],
    'roles' => ['Admin']
        ]);
Route::fallback(function(){
    return view('errors.404');
});



