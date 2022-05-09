<?php

namespace App\View;

use Doctrine\DBAL\Connection;
use Money\Currency;
use Money\Money;

class DbalPayrolls implements Payrolls
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public function listPayrolls(?PayrollFilter $filter, ?PayrollOrder $order): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $results = $queryBuilder
            ->select('e.name, d.name as department_name, e.hire_date, e.base_salary_amount, e.base_salary_currency, d.serialized_bonus')
            ->from('employee', 'e')
            ->leftJoin('e', 'department', 'd', 'e.department_id = d.id')
            ->fetchAllAssociative();

        $payrolls = (array_map(function (array $row) {
            $yearsOfService = (new \DateTimeImmutable('now'))->diff(new \DateTimeImmutable($row['hire_date']))->y;
            $baseSalary = new Money($row['base_salary_amount'], new Currency($row['base_salary_currency']));
            $bonus = unserialize($row['serialized_bonus']);
            $bonusSalary = $bonus->calculate($baseSalary, $yearsOfService);
            return new Payroll(
                $row['name'],
                $row['department_name'],
                $baseSalary,
                $bonusSalary,
                $bonus,
                $baseSalary->add($bonusSalary)
            );
        }, $results));
//
//        if (null !== $filter) {
//            $payrolls = array_values(array_filter(
//                $payrolls,
//                fn(Payroll $payroll) => $payroll->{$filter->field->value} === $filter->value
//            ));
//        }
//
//        if (null !== $order) {
//            usort(
//                $payrolls,
//                fn($a, $b) => ($order->ascending ? 1 : -1) * ($a->{$order->type->value} <=> $b->{$order->type->value})
//            );
//        }
//
        return $payrolls;
    }
}
