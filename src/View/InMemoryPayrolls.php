<?php

namespace App\View;

use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;

class InMemoryPayrolls implements Payrolls
{
    private EmployeeRepository $employeeRepository;
    private DepartmentRepository $departmentRepository;

    public function __construct(EmployeeRepository $employeeRepository, DepartmentRepository $departmentRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function listPayrolls(?PayrollFilter $filter): array
    {
        $employees = $this->employeeRepository->findAll();
        $departmentRepository = $this->departmentRepository;
        $payrolls = (array_map(function (Employee $employee) use ($departmentRepository) {
            $department = $departmentRepository->get($employee->departmentId);
            $yearsOfService = (new \DateTimeImmutable('now'))->diff($employee->hireDate)->y;
            $baseSalary = $employee->baseSalary;
            $bonusSalary = $department->bonus->calculate($employee->baseSalary, $yearsOfService);
            return new Payroll(
                $employee->name,
                $department->name,
                $baseSalary,
                $bonusSalary,
                $department->bonus,
                $baseSalary->add($bonusSalary)
            );
        }, $employees));

        if (null !== $filter) {
            $payrolls = array_values(array_filter(
                $payrolls,
                fn(Payroll $payroll) => $payroll->{$filter->field->value} === $filter->value
            ));
        }

        return $payrolls;
    }
}
