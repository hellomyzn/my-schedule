<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;

Route::middleware(['can:user-higher', 'auth'])
    ->group(function(){
        Route::get('/', function(){
            return view('calendar');
        })->name('calendar');
});