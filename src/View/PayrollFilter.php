<?php

namespace App\View;

class PayrollFilter
{
    public readonly PayrollFilterType $field;
    public readonly string $value;

    public function __construct(PayrollFilterType $type, string $value)
    {
        $this->field = $type;
        $this->value = $value;
    }
}
