<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;

$factory->define(Transaction::class, function () {
    return [
        'type' => TransactionType::BUY,
        'price_per_coin' => 1,
        'quantity' => 1,
        'fee' => 1,
        'datetime' => now(),
        'portfolio_id' => Portfolio::first()->id,
        'coin_id' => Coin::first()->id,
    ];
});
