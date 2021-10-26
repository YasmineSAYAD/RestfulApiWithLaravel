<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home/myTokens', [App\Http\Controllers\HomeController::class, 'getTokens'])->name('personal-tokens');
Route::get('/home/myClients', [App\Http\Controllers\HomeController::class, 'getClients'])->name('personal-clients');
Route::get('/home/authorizedClients', [App\Http\Controllers\HomeController::class, 'getAuthorizedClients'])->name('authorized-clients');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
