<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
class Employee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=32)
     */
    public readonly string $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    public readonly string $name;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    public readonly \DateTimeImmutable $hireDate;
    /**
     * @ORM\Column(type="string", length=32)
     */
    public readonly string $departmentId;
    /**
     * @ORM\Embedded(class="Money\Money")
     */
    public readonly Money $baseSalary;

    public function __construct(string $name, string $departmentId, \DateTimeImmutable $hireDate, Money $baseSalary)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->hireDate = $hireDate;
        $this->departmentId = $departmentId;
        $this->baseSalary = $baseSalary;
    }
}
