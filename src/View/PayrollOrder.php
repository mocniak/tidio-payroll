<?php

namespace App\View;

class PayrollOrder
{
    public readonly PayrollOrderType $type;
    public readonly bool $ascending;

    public function __construct(PayrollOrderType $type, bool $ascending)
    {
        $this->type = $type;
        $this->ascending = $ascending;
    }
}
