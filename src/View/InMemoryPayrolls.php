<?php

namespace App\View;

use App\Entity\Employee;
use App\Repository\InMemoryDepartmentRepository;
use App\Repository\InMemoryEmployeeRepository;

class InMemoryPayrolls implements Payrolls
{
    private InMemoryEmployeeRepository $employeeRepository;
    private InMemoryDepartmentRepository $departmentRepository;

    public function __construct(InMemoryEmployeeRepository $employeeRepository, InMemoryDepartmentRepository $departmentRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @inheritdoc
     */
    public function listPayrolls(?PayrollFilter $filter, ?PayrollOrder $order): array
    {
        $employees = $this->employeeRepository->findAll();
        $departmentRepository = $this->departmentRepository;
        $payrolls = (array_map(function (Employee $employee) use ($departmentRepository) {
            $department = $departmentRepository->get($employee->departmentId);
            $yearsOfService = (new \DateTimeImmutable('now'))->diff($employee->hireDate)->y;
            $baseSalary = $employee->baseSalary;
            $bonusSalary = $department->bonus()->calculate($employee->baseSalary, $yearsOfService);
            return new Payroll(
                $employee->name,
                $department->name,
                $baseSalary,
                $bonusSalary,
                $department->bonus(),
                $baseSalary->add($bonusSalary)
            );
        }, $employees));

        if (null !== $filter) {
            $payrolls = array_values(array_filter(
                $payrolls,
                fn(Payroll $payroll) => $payroll->{$filter->field->value} === $filter->value
            ));
        }

        if (null !== $order) {
            usort(
                $payrolls,
                fn($a, $b) => ($order->ascending ? 1 : -1) * ($a->{$order->type->value} <=> $b->{$order->type->value})
            );
        }

        return $payrolls;
    }
}
