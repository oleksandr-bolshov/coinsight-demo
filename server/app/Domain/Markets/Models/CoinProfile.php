<?php

declare(strict_types=1);

namespace App\Domain\Markets\Models;

use Illuminate\Database\Eloquent\Model;

final class CoinProfile extends Model
{
    protected $table = 'coin_profiles';

    protected $dates = [
        'genesis_date',
    ];
}
