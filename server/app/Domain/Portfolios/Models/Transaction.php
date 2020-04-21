<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Models;

use App\Domain\Markets\Models\Coin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Transaction extends Model
{
    protected $casts = [
        'price_per_coin' => 'float',
        'quantity' => 'float',
        'fee' => 'float',
        'datetime' => 'datetime',
    ];

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }
}
