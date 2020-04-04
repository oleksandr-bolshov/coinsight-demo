<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Users\Models\Session;
use App\Domain\Users\Models\User;

$factory->define(Session::class, function () {
    return [
        'created_at' => now(),
        'last_used_at' => now(),
        'user_id' => User::first()->id,
    ];
});
