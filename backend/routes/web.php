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
})->name('welcome');

Route::fallback(function(){ //存在しないURLは自動的にTOPにリダイレクトさせる。
    return redirect(route('welcome')); 
});

require __DIR__.'/auth.php';
require __DIR__.'/admins/web.php';
require __DIR__.'/managers/web.php';
require __DIR__.'/users/web.php';