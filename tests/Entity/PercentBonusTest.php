<?php

namespace App\Tests\Entity;

use App\Entity\PercentBonus;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PercentBonusTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testBonusIsProportionalToBaseSalary(int $bonusPercent, int $baseSalary, int $expectedBonus)
    {
        $bonus =  new PercentBonus($bonusPercent);
        $yearsOfService = 123;
        $baseSalary = Money::USD($baseSalary);
        $this->assertEquals(Money::USD($expectedBonus), $bonus->calculate($baseSalary, $yearsOfService));
    }

    public function dataProvider(): array
    {
        return [
            'no bonus when bonus is set to 0 percent' =>
                [0, 1000_00, 0_00],
            'no bonus when zero base salary' =>
                [10, 0, 0_00],
            'test for 1% bonus' =>
                [1, 1000_00, 10_00],
            'test for 5% bonus' =>
                [5, 1000_00, 50_00],
            'test for 100% bonus' =>
                [100, 1000_00, 1000_00],
        ];
    }
}
