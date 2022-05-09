<?php

namespace App\Repository;

use App\Entity\Employee;

interface EmployeeRepository
{
    public function add(Employee $employee): void;

    /**
     * @return Employee[]
     */
    public function findAll(): array;

    public function removeAll(): void;
}
