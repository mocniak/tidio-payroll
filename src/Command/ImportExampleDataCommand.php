<?php

namespace App\Command;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\LongevityBonus;
use App\Entity\PercentBonus;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use Money\Money;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportExampleDataCommand extends Command
{
    protected static $defaultName = 'app:import-example-data';
    protected static $defaultDescription = 'Load to a database set of example employees and departments';
    private DepartmentRepository $departmentRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct(DepartmentRepository $departmentRepository, EmployeeRepository $employeeRepository)
    {

        parent::__construct();
        $this->departmentRepository = $departmentRepository;
        $this->employeeRepository = $employeeRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $departments = [
            new Department('HR', new LongevityBonus(Money::USD(100_00))),
            new Department('Customer Service', new PercentBonus(15)),
            new Department('IT', new LongevityBonus(Money::USD(50_00))),
        ];

        foreach ($departments as $department) {
            $this->departmentRepository->add($department);
        }

        $employees = [
            ['John Doe', 900_00, 'HR', '2015-01-01'],
            ['Jane Doe', 1500_00, 'HR', '2017-01-01'],
            ['Jacob Smith', 1500_00, 'HR', '2021-01-01'],
            ['Logan Dawson', 1200_00, 'Customer Service', '2018-01-01'],
            ['Milan Stevens', 1300_00, 'Customer Service', '2005-01-01'],
            ['Lennie Howard', 1700_00, 'Customer Service', '2010-01-01'],
            ['Hayden Foster', 1500_00, 'Customer Service', '2011-01-01'],
            ['Cooper Sharp', 1200_00, 'IT', '2018-01-01'],
            ['Luca Hayes', 1800_00, 'IT', '2011-01-01'],
            ['Jaiden Shaw', 1300_00, 'IT', '2014-01-01'],
            ['Luca White', 1200_00, 'IT', '2018-01-01'],
            ['Prince Doyle', 1600_00, 'IT', '2019-01-01'],
        ];

        foreach ($employees as $employee) {
            $departmentId = $this->departmentRepository->getByName($employee[2])->id;
            $this->employeeRepository->add(new Employee(
                $employee[0],
                $departmentId,
                new \DateTimeImmutable($employee[3]),
                Money::USD($employee[1])
            ));
        }
        $output->write('[OK] Example employees and departments imported.');

        return Command::SUCCESS;
    }
}
