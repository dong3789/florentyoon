<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function(){
    Route::get('/users', [\App\Http\Controllers\CatUsersController::class, 'getUsersData']);
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
