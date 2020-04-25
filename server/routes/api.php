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
    ->get('/global', 'GlobalStats@getGlobalStats');

Route::group([
    'prefix' => 'coins',
    'middleware' => 'token:access'
], function () {
    Route::get('/', 'CoinController@getCoins');
    Route::get('/{id}/profile', 'CoinController@getCoinProfile');
    Route::get('/{id}/latest', 'CoinController@getCoinMarketData');
    Route::get('/{id}/historical', 'CoinController@getCoinHistoricalData');
});

Route::group([
    'prefix' => 'portfolios',
    'middleware' => 'token:access',
], function () {
    Route::post('/', 'PortfolioController@createPortfolio');
    Route::get('/', 'PortfolioController@getPortfolios');
});

Route::group([
    'prefix' => 'transactions',
    'middleware' => 'token:access',
], function () {
    Route::post('/', 'TransactionController@createTransaction');
    Route::get('/', 'TransactionController@getTransactions');
});
