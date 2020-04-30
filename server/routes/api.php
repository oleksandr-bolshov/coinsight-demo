<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Users\Controllers')->group(function () {
    Route::group([
        'prefix' => 'auth',
    ], function() {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::middleware('token:access')
            ->get('/me', 'AuthController@me');
    });

    Route::group([
        'prefix' => 'sessions',
    ], function() {
        Route::group([
            'middleware' => 'token:access'
        ], function () {
            Route::get('/', 'SessionController@getSessions');
            Route::put('/terminate', 'SessionController@terminate');
        });

        Route::middleware('token:refresh')
            ->get('/refresh', 'SessionController@getAccessToken');
    });
});

Route::namespace('Markets\Controllers')->group(function () {
    Route::middleware('token:access')
        ->get('/global', 'GlobalStatsController@getGlobalStats');

    Route::group([
        'prefix' => 'coins',
        'middleware' => 'token:access'
    ], function () {
        Route::get('/', 'CoinController@getCoins');
        Route::get('/{id}/profile', 'CoinController@getCoinProfile');
        Route::get('/{id}/latest', 'CoinController@getCoinMarketData');
        Route::get('/{id}/historical', 'CoinController@getCoinHistoricalData');
    });
});

Route::namespace('Portfolios\Controllers')->group(function () {
    Route::group([
        'prefix' => 'portfolios',
        'middleware' => 'token:access',
    ], function () {
        Route::post('/', 'PortfolioController@createPortfolio');
        Route::get('/', 'PortfolioController@getPortfolios');
        Route::get('/{id}', 'PortfolioController@getPortfolioReportById');
        Route::put('/{id}', 'PortfolioController@updatePortfolioById');
        Route::delete('/{id}', 'PortfolioController@deletePortfolioById');
    });

    Route::group([
        'prefix' => 'transactions',
        'middleware' => 'token:access',
    ], function () {
        Route::post('/', 'TransactionController@createTransaction');
        Route::get('/', 'TransactionController@getTransactions');
        Route::put('/{id}', 'TransactionController@updateTransactionById');
        Route::delete('/{id}', 'TransactionController@deleteTransactionById');
    });
});

