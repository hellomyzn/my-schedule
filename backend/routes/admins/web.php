<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth', 'can:admin'])
    ->group(function(){
        Route::get('index', function() {
            dd('admin');
        });
});