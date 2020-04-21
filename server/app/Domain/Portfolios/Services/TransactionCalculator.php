<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

final class TransactionCalculator
{
    public function cost(float $quantity, float $pricePerCoin, float $fee): float
    {
        return $quantity * $pricePerCoin + $fee;
    }

    public function value(float $quantity, float $currentPrice): float
    {
        return $quantity * $currentPrice;
    }

    public function valueChange(float $currentValue, float $cost): float
    {
        return ($currentValue - $cost) / $cost * 100;
    }
}
