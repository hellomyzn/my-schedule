<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')
    ->middleware(['can:manager-higher'])
    ->group(function(){
        Route::get('index', function() {
            dd('manager');
        });
});