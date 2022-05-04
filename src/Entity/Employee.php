<?php

namespace App\Entity;

use Money\Money;

class Employee
{
    public readonly string $id;
    public readonly string $name;
    public readonly \DateTimeImmutable $hireDate;
    public readonly string $departmentId;
    public readonly Money $baseSalary;

    public function __construct(string $name, string $departmentId, \DateTimeImmutable $hireDate, Money $baseSalary)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->hireDate = $hireDate;
        $this->departmentId = $departmentId;
        $this->baseSalary = $baseSalary;
    }
}
