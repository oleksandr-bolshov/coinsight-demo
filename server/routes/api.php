<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function() {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::get('/me', 'AuthController@me')->middleware('token:access');
});

Route::group([
    'prefix' => 'sessions'
], function() {
    Route::get('/access-token', 'SessionController@getAccessToken')->middleware('token:refresh');
});
