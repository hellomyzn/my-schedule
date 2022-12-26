<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;


Route::prefix('manager')
    ->middleware(['can:manager-higher'])
    ->name('managers.')
    ->group(function(){
        Route::prefix('events')
        ->name('events.')
        ->controller(EventController::class)
        ->group(function(){
            Route::get('past', 'past')->name('past');
    });

    Route::resource('events', EventController::class);
});