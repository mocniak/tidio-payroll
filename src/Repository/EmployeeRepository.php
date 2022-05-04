<?php

namespace App\Repository;

use App\Entity\Employee;

class EmployeeRepository
{
    private array $employees;

    public function __construct()
    {
        $this->employees = [];
    }

    public function add(Employee $employee): void
    {
        $this->employees[$employee->id] = $employee;
    }

    /**
     * @return Employee[]
     */
    public function findAll(): array
    {
        return array_values($this->employees);
    }
}
