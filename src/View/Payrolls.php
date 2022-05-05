<?php

namespace App\View;

interface Payrolls
{
    /**
     * @return Payroll[]
     */
    public function listPayrolls(?PayrollFilter $filter, ?PayrollOrder $order): array;
}
