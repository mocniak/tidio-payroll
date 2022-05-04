<?php

namespace App\Tests\Entity;

use App\Entity\LongevityBonus;
use Money\Money;
use PHPUnit\Framework\TestCase;

class LongevityBonusTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCalculateLongevityBonus(int $bonus, int $yearsOfService, int $baseSalary, int $expectedBonus)
    {
        $bonus = new LongevityBonus(Money::USD($bonus));
        $baseSalary = Money::USD($baseSalary);
        $this->assertEquals(Money::USD($expectedBonus), $bonus->calculate($baseSalary, $yearsOfService));
    }

    public function dataProvider(): array
    {
        return [
            'no bonus when 0 years of service' =>
                [100_00, 0, 1000_00, 0_00],
            'bonus is proportional to years of service' =>
                [100_00, 5, 1000_00, 500_00],
            'bonus rises for first 10 years only ' =>
                [100_00, 10, 1000_00, 1000_00],
            'after eleven years bonus is same as after ten years' =>
                [100_00, 11, 1000_00, 1000_00],
            'after 100 years bonus is still the same' =>
                [100_00, 100, 1000_00, 1000_00],
            'no bonus when no bonus' =>
                [0, 0, 0, 0],
            'bonus is still counted when base salary is 0' =>
                [100_00, 5, 0, 500_00],
        ];
    }
}
