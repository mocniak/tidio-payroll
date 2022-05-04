<?php

namespace App\View;

interface Payrolls
{
    public function listPayrolls(?PayrollFilter $filter): array;
}
