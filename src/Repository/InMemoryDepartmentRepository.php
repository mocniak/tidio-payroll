<?php

namespace App\Repository;

use App\Entity\Department;

class InMemoryDepartmentRepository implements DepartmentRepository
{
    private array $departments;

    public function __construct()
    {
        $this->departments = [];
    }

    public function add(Department $department): void
    {
        $this->departments[$department->id] = $department;
    }

    public function getByName(string $departmentName): Department
    {
        return array_values(array_filter($this->departments, function (Department $department) use ($departmentName) {
            return $departmentName === $department->name;
        }))[0];
    }

    public function get(string $id): Department
    {
        return $this->departments[$id];
    }

    public function removeAll(): void
    {
        $this->departments = [];
    }
}
