<?php

namespace App\Tests\View;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\PercentBonus;
use App\Repository\InMemoryDepartmentRepository;
use App\Repository\InMemoryEmployeeRepository;
use App\View\InMemoryPayrolls;
use App\View\Payroll;
use App\View\PayrollFilter;
use App\View\PayrollFilterType;
use App\View\PayrollOrder;
use App\View\PayrollOrderType;
use Money\Money;
use PHPUnit\Framework\TestCase;

class InMemoryPayrollsTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilteringPayrolls(array $employees, array $filter, array $expectedEmployees)
    {
        $employeeRepository = new InMemoryEmployeeRepository();
        $departmentRepository = new InMemoryDepartmentRepository();
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
        }, $payrolls->listPayrolls(count($filter) === 0 ? null : new PayrollFilter($filter[0], $filter[1]), null)));
    }

    public function filterProvider(): array
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

    /**
     * @dataProvider orderProvider
     */
    public function testOrderingPayrolls(PayrollOrderType $orderType, bool $ascending, array $expectedEmployees)
    {
        $employeeRepository = new InMemoryEmployeeRepository();
        $departmentRepository = new InMemoryDepartmentRepository();
        $departmentRepository->add(new Department('Department 1', new PercentBonus(0)));
        $departmentRepository->add(new Department('Department 2', new PercentBonus(0)));
        $employees = [
            ['name' => 'Adam Brown', 'department' => 'Department 2', 'baseSalary' => 3000_00],
            ['name' => 'John Doe', 'department' => 'Department 1', 'baseSalary' => 2000_00],
            ['name' => 'Jane Smith', 'department' => 'Department 2', 'baseSalary' => 1000_00],
        ];
        foreach ($employees as $employee) {
            $employeeRepository->add(new Employee(
                $employee['name'],
                $departmentRepository->getByName($employee['department'])->id,
                new \DateTimeImmutable('now'),
                Money::USD($employee['baseSalary'])
            ));
        }
        $payrolls = new InMemoryPayrolls($employeeRepository, $departmentRepository);
        $this->assertEquals($expectedEmployees, array_map(function (Payroll $payroll) {
            return $payroll->employeeName;
        }, $payrolls->listPayrolls(null, new PayrollOrder($orderType, $ascending))));
    }

    public function orderProvider(): array
    {
        return [
            'Order by employee name ASC' => [
                PayrollOrderType::EMPLOYEE_NAME,
                true,
                ['Adam Brown', 'Jane Smith', 'John Doe'],
            ],
            'Order by employee name DESC' => [
                PayrollOrderType::EMPLOYEE_NAME,
                false,
                ['John Doe', 'Jane Smith', 'Adam Brown'],
            ],
            'Order by base salary ASC' => [
                PayrollOrderType::BASE_SALARY,
                true,
                ['Jane Smith', 'John Doe', 'Adam Brown'],
            ],
            'Order by base salary DESC' => [
                PayrollOrderType::BASE_SALARY,
                false,
                ['Adam Brown', 'John Doe', 'Jane Smith'],
            ],
            'Order by department name ASC' => [
                PayrollOrderType::DEPARTMENT,
                true,
                ['John Doe', 'Adam Brown', 'Jane Smith'],
            ],
        ];
    }
}
