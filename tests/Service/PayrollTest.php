<?php

namespace App\Tests\Service;

use App\Entity\LongevityBonus;
use App\Entity\Department;
use App\Service\Payroll;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PayrollTest extends TestCase
{
    public function testConstantBonusIsAddedToBaseSalaryForEveryYearOfService()
    {
        $payroll = new Payroll();
        $department = new Department("HR", new LongevityBonus(Money::USD(100_00)));
        $hireDate = new \DateTimeImmutable('5 years ago');
        $baseSalary = Money::USD(1000_00);
        $this->assertEquals(Money::USD(1500_00), $payroll->calculateSalary($baseSalary, $hireDate, $department));
    }

    public function testConstantBonusIsCountedForAtMost10YearsOfService()
    {
        $payroll = new Payroll();
        $department = new Department("HR", new LongevityBonus(Money::USD(100_00)));
        $hireDate = new \DateTimeImmutable('15 years ago');
        $baseSalary = Money::USD(1000_00);
        $this->assertEquals(Money::USD(2000_00), $payroll->calculateSalary($baseSalary, $hireDate, $department));
    }
}
