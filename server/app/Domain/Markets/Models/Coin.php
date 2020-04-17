<?php

declare(strict_types=1);

namespace App\Domain\Markets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Coin extends Model
{
    public function profile(): HasOne
    {
        return $this->hasOne(CoinProfile::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CoinLink::class);
    }
}
