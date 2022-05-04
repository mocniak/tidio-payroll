<?php

namespace App\Tests\View;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\PercentBonus;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\View\InMemoryPayrolls;
use App\View\Payroll;
use App\View\PayrollFilter;
use App\View\PayrollFilterType;
use Money\Money;
use PHPUnit\Framework\TestCase;

class InMemoryPayrollsTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testDisplayAllEmployees(array $employees, array $filter, array $expectedEmployees)
    {
        $employeeRepository = new EmployeeRepository();
        $departmentRepository = new DepartmentRepository();
        $departmentRepository->add(new Department('Department 1', new PercentBonus(0)));
        $departmentRepository->add(new Department('Department 2', new PercentBonus(0)));
        foreach ($employees as $employee) {
            $employeeRepository->add(new Employee(
                $employee['name'],
                $departmentRepository->getByName($employee['department'])->id,
                new \DateTimeImmutable('now'),
                Money::USD(100_00)
            ));
        }
        $payrolls = new InMemoryPayrolls($employeeRepository, $departmentRepository);
        $this->assertEquals($expectedEmployees, array_map(function (Payroll $payroll) {
            return $payroll->employeeName;
        }, $payrolls->listPayrolls(count($filter) === 0 ? null : new PayrollFilter($filter[0], $filter[1]))));
    }

    public function dataProvider(): array
    {
        return [
            'Filter by employee name' => [
                [
                    ['name' => 'John Doe', 'department' => 'Department 1'],
                    ['name' => 'Jane Smith', 'department' => 'Department 2'],
                ],
                [PayrollFilterType::EMPLOYEE_NAME, 'John Doe'],
                ['John Doe'],
            ],
            'Filter by department name' => [
                [
                    ['name' => 'John Doe', 'department' => 'Department 1'],
                    ['name' => 'Jane Smith', 'department' => 'Department 2'],
                ],
                [PayrollFilterType::DEPARTMENT, 'Department 2'],
                ['Jane Smith'],
            ],
            'No results when there are no employees' => [
                [],
                [PayrollFilterType::EMPLOYEE_NAME, 'John Doe'],
                [],
            ],
            'All results when there are no filter' => [
                [
                    ['name' => 'John Doe', 'department' => 'Department 1'],
                    ['name' => 'Jane Smith', 'department' => 'Department 2'],
                ],
                [],
                ['John Doe', 'Jane Smith'],
            ],
        ];
    }
}
