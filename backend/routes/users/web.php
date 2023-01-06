<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;


Route::middleware(['can:user-higher', 'auth'])
    ->group(function(){
        
        // reservation 
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

        Route::prefix('mypage')
            ->name('mypage.')
            ->controller(MyPageController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/{event}', 'show')->name('show');
                Route::post('/{event}', 'cancel')->name('cancel');
            });
        
});