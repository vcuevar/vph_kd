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
Auth::routes();
//Route::post('login', );

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
   
});

Route::get('/test','TestController@test'); 

//Route::get('/home/registro_alumno', 'AlumnoController@registro')
Route::get('/home/registro_alumno', function(){
 return view('alumnos.registro');
});

Route::post('guardar_alumno', 'AlumnoController@store');