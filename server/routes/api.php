<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function() {
   Route::post('/register', 'AuthController@register');
});
