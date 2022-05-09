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

        $queryBuilder
            ->select('e.name, d.name as departmentName, e.hireDate, e.baseSalary_amount, e.baseSalary_currency, d.serializedBonus')
            ->from('employees', 'e')
            ->leftJoin('e', 'departments', 'd', 'e.departmentId = d.id');

//        if (null !== $filter) {
//            $queryBuilder
//                ->where(':filterColumn = :value')
//                ->setParameter('filterColumn',$filter->field->value);
//        }
//
//        if (null !== $order) {
//            usort(
//                $payrolls,
//                fn($a, $b) => ($order->ascending ? 1 : -1) * ($a->{$order->type->value} <=> $b->{$order->type->value})
//            );
//        }

        $results = $queryBuilder->fetchAllAssociative();

        return (array_map(function (array $row) {
            $yearsOfService = (new \DateTimeImmutable('now'))->diff(new \DateTimeImmutable($row['hireDate']))->y;
            $baseSalary = new Money($row['baseSalary_amount'], new Currency($row['baseSalary_currency']));
            $bonus = unserialize($row['serializedBonus']);
            $bonusSalary = $bonus->calculate($baseSalary, $yearsOfService);
            return new Payroll(
                $row['name'],
                $row['departmentName'],
                $baseSalary,
                $bonusSalary,
                $bonus,
                $baseSalary->add($bonusSalary)
            );
        }, $results));
    }
}
