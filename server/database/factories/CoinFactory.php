<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Markets\Models\Coin;
use Illuminate\Support\Str;

$factory->define(Coin::class, function () {
    $randomString = Str::random();

    return [
        'name' => $randomString,
        'symbol' => $randomString,
        'icon' => $randomString,
    ];
});
