<?php

namespace App\Entity;

use Money\Money;

class PercentBonus implements Bonus
{
    private int $amountInPercents;

    public function __construct(int $amountInPercents)
    {
        $this->amountInPercents = $amountInPercents;
    }

    public function calculate(Money $baseSalary, int $yearsOfService): Money
    {
        return $baseSalary->multiply(0.01 * $this->amountInPercents);
    }
}
