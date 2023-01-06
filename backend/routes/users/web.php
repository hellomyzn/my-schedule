<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;

Route::middleware(['can:user-higher', 'auth'])
    ->group(function(){
        Route::get('/', function(){
            return view('calendar');
        })->name('calendar');

        Route::controller(ReservationController::class)
            ->group(function(){
                Route::get('/dashboard', 'dashboard')->name('dashboard');

                Route::prefix('events')
                    ->name('events.')
                    ->group(function() {
                        Route::get('/{event}', 'detail')->name('detail');
                        Route::post('/{event}', 'reserve')->name('reserve');
                    });
            });

});