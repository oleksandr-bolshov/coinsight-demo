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
    Route::group([
        'middleware' => 'token:access'
    ], function () {
        Route::get('/', 'SessionController@getSessions');
        Route::put('/terminate', 'SessionController@terminate');
    });

    Route::get('/access-token', 'SessionController@getAccessToken')->middleware('token:refresh');
});


Route::middleware('token:access')
    ->get('/global', 'MarketController@getGlobalStats');

Route::group([
    'prefix' => 'coins',
    'middleware' => 'token:access'
], function () {
    Route::get('/', 'MarketController@getCoins');
    Route::get('/{id}/profile', 'MarketController@getCoinProfile');
    Route::get('/{id}/latest', 'MarketController@getCoinMarketData');
    Route::get('/{id}/historical', 'MarketController@getCoinHistoricalData');
});
