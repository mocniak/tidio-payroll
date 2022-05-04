<?php

namespace App\View;

use App\Entity\Bonus;
use Money\Money;

class Payroll
{
    public readonly string $employeeName;
    public readonly string $departmentName;
    public readonly string $baseSalary;
    public readonly string $bonusSalary;
    public readonly string $bonusType;
    public readonly string $totalSalary;

    public function __construct(
        string $employeeName,
        string $departmentName,
        Money $baseSalary,
        Money $bonusSalary,
        Bonus $bonus,
        Money $totalSalary
    )
    {
        $this->employeeName = $employeeName;
        $this->departmentName = $departmentName;
        $this->baseSalary = $baseSalary->getAmount();
        $this->bonusSalary = $bonusSalary->getAmount();
        $this->bonusType = $bonus->name();
        $this->totalSalary = $totalSalary->getAmount();
    }
}
