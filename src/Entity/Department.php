<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Department
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
     * @ORM\Column (type="text")
     */
    private readonly string $serializedBonus;

    public function __construct(string $name, Bonus $bonus)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->serializedBonus = serialize($bonus);
    }

    public function bonus(): Bonus
    {
        return unserialize($this->serializedBonus);
    }
}
