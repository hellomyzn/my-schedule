<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['can:user-higher'])
    ->group(function(){
        Route::get('index', function() {
            dd('user');
        });
});