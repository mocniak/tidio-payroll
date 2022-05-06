<?php

namespace App\Entity;

use Money\Money;

interface Bonus
{
    public function calculate(Money $baseSalary, int $yearsOfService): Money;

    public function name(): string;
}
