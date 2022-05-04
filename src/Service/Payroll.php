<?php

namespace App\Service;

use App\Entity\Department;
use Money\Money;

class Payroll
{
    public function calculateSalary(Money $baseSalary, \DateTimeImmutable $hireDate, Department $department): Money
    {
        $yearsOfService = (new \DateTimeImmutable('now'))->diff($hireDate)->y;
        $bonus = $department->bonus->getBonusForASalary($baseSalary)->multiply($yearsOfService);
        return $baseSalary->add($bonus);
    }
}
