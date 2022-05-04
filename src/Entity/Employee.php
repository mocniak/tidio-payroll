<?php

namespace App\Entity;

class Employee
{
    public readonly string $id;
    public readonly string $name;
    public readonly \DateTimeImmutable $hireDate;
    public readonly string $departmentId;

    public function __construct(string $name, string $departmentId, \DateTimeImmutable $hireDate)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->hireDate = $hireDate;
        $this->departmentId = $departmentId;
    }
}
