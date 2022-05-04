<?php

namespace App\Entity;

use Money\Money;

class LongevityBonus implements Bonus
{
    const YEARS_OF_SERVICE_LIMIT = 10;
    public readonly Money $bonusAmount;

    public function __construct(Money $bonusAmount)
    {
        $this->bonusAmount = $bonusAmount;
    }

    public function calculate(Money $baseSalary, int $yearsOfService): Money
    {
        return $this->bonusAmount->multiply(min($yearsOfService, self::YEARS_OF_SERVICE_LIMIT));
    }
}
