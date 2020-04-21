<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Users\Models\User;

$factory->define(Portfolio::class, function () {
    return [
        'name' => 'name',
        'user_id' => User::inRandomOrder()->pluck('id')->first(),
    ];
});
