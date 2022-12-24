<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['can:admin'])
    ->group(function(){
        Route::get('index', function() {
            dd('admin');
        });
});