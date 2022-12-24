<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;


Route::prefix('manager')
    ->middleware(['can:manager-higher'])
    ->name('managers.')
    ->controller(EventController::class)
    ->group(function(){
        Route::resource('events', EventController::class);
});