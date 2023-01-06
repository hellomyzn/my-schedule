<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;

Route::middleware(['can:user-higher', 'auth'])
    ->group(function(){
        Route::get('/', function(){
            return view('calendar');
        })->name('calendar');

        Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
        Route::get('/{id}', [ReservationController::class, 'detail'])->name('events.detail');
        Route::post('/{id}', [ReservationController::class, 'reserve'])->name('events.reserve');
});