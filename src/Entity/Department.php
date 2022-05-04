<?php

namespace App\Entity;

class Department
{
    public readonly string $id;
    public readonly string $name;
    public readonly Bonus $bonus;

    public function __construct(string $name, Bonus $bonus)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->bonus = $bonus;
    }
}
