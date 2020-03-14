<?php

declare(strict_types=1);

namespace App\Domains\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class Session extends Model
{
    public $timestamps = false;

    protected $dates = [
        'created_at',
        'last_used_at',
        'terminated_at',
    ];

    public function scopeActive(Builder  $query): Builder
    {
        return $query->where('terminated_at', null);
    }
}
