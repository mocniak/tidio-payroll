<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/payroll", name="payroll")
 */
class PayrollController
{
    private EmployeeRepository $employeeRepository;
    private DepartmentRepository $departmentRepository;

    public function __construct(EmployeeRepository $employeeRepository, DepartmentRepository $departmentRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function __invoke(): Response
    {
        $employees = $this->employeeRepository->findAll();
        $departmentRepository = $this->departmentRepository;
        return new JsonResponse(array_map(function (Employee $employee) use ($departmentRepository) {
            $department = $departmentRepository->get($employee->departmentId);
            $yearsOfService = (new \DateTimeImmutable('now'))->diff($employee->hireDate)->y;
            $baseSalary = $employee->baseSalary;
            $bonusSalary = $department->bonus->calculate($employee->baseSalary, $yearsOfService);
            return [
                'name' => $employee->name,
                'department' => $department->name,
                'baseSalary' => $baseSalary->getAmount(),
                'bonus' => $bonusSalary->getAmount(),
                'bonusType' => $department->bonus->name(),
                'totalSalary' => $baseSalary->add($bonusSalary)->getAmount()
            ];
        }, $employees));
    }
}
