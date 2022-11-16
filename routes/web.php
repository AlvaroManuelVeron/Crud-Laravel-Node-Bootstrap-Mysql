<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;//usamos esta referencia para acceder al Controller

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
    return view('auth.login');
});

/*
Route::get('/empleado', function () {
    return view('empleado.index');
});

Route::get('/empleado/create',[EmpleadoController::class,'create']);//Cuando el usuario escriba empleado/create 
                                                                    //vamos a acceder a la clase EmpleadoController
                                                                    //al metodo 'create'
                    
*/
                                                                    
Route::resource('empleado', EmpleadoController::class)->middleware('auth');//Con esto puedo usar todas las routes

Auth::routes(['register'=> false,'reset'=> false]);//elimino del login la opcion de registrar y olvidar contraseña
//Auth::routes(['register' => false]);
//Auth::routes([
   // 'register' => false, // Registration Routes...
   // 'reset' => false, // Password Reset Routes...
   // 'verify' => false, // Email Verification Routes...
 // ]);
Route::get('/home', [EmpleadoController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [EmpleadoController::class, 'index'])->name('home');
    
});




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
