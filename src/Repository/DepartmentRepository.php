<?php

namespace App\Repository;

use App\Entity\Department;

interface DepartmentRepository
{
    public function add(Department $department): void;

    public function getByName(string $departmentName): Department;

    public function get(string $id): Department;

    public function removeAll(): void;
}
