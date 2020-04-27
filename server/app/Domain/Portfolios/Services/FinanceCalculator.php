<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

final class FinanceCalculator
{
    public function cost(float $quantity, float $pricePerCoin, float $fee): float
    {
        return $quantity * $pricePerCoin + $fee;
    }

    public function value(float $quantity, float $price): float
    {
        return $quantity * $price;
    }

    public function valueChange(float $currentValue, float $cost): float
    {
        return ($currentValue - $cost) / $cost * 100;
    }

    public function netProfit(float $currentValue, float $netCost): float
    {
        return $currentValue - $netCost;
    }

    public function share(float $assetValue, float $portfolioValue): float
    {
        return 100 * $assetValue / $portfolioValue;
    }
}
