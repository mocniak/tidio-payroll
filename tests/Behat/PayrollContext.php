<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\LongevityBonus;
use App\Entity\PercentBonus;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use Behat\Behat\Context\Context;
use Money\Money;
use Webmozart\Assert\Assert;

final class PayrollContext implements Context
{
    private WebClient $webClient;
    private DepartmentRepository $departmentRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct(WebClient $webClient, DepartmentRepository $departmentRepository,
        EmployeeRepository $employeeRepository)
    {
        $this->webClient = $webClient;
        $this->departmentRepository = $departmentRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @Given there is a :departmentName department having :bonusType bonus of :bonusAmount
     */
    public function thereIsADepartmentHavingBonusOf(string $departmentName, string $bonusType,
        string|Money $bonusAmount)
    {
        if ($bonusType === "percentage") {
            $bonus = new PercentBonus((int)str_replace('%', '', $bonusAmount));
        } elseif ($bonusType === "longevity") {
            $bonus = new LongevityBonus($bonusAmount);
        } else {
            throw new \RuntimeException("bad bonus type");
        }
        $this->departmentRepository->add(new Department($departmentName, $bonus));
    }

    /**
     * @Given there is an employee :employeeName with base salary :baseSalary working in :departmentName department for :yearsOfService years
     */
    public function thereIsAnEmployeeWithBaseSalaryWorkingInDepartmentForYears(
        string $employeeName,
        Money $baseSalary,
        string $departmentName,
        int $yearsOfService
    )
    {
        $departmentId = $this->departmentRepository->getByName($departmentName)->id;
        $hireDate = new \DateTimeImmutable($yearsOfService . ' years ago');
        $this->employeeRepository->add(new Employee($employeeName, $departmentId, $hireDate, $baseSalary));
    }

    /**
     * @When I display a payrolls
     */
    public function iDisplayAPayrolls()
    {
        $this->webClient->fetch('/api/payroll');
    }

    /**
     * @Then I see :numberOfResults results
     */
    public function iSeeResults(int $numberOfResults)
    {
        Assert::eq(count($this->webClient->getLatestResponseContent()), $numberOfResults);
    }

    /**
     * @Then I see that :employeeName is working in :departmentName department
     */
    public function iSeeThatIsWorkingInDepartment(string $employeeName, string $departmentName)
    {
        Assert::notEmpty(array_filter(
            $this->webClient->getLatestResponseContent(),
            fn($row) => $row['name'] === $employeeName && $row['department'] === $departmentName
        ));
    }

    /**
     * @Then I see that :employeeName has base salary :baseSalary and :bonusType bonus :bonusAmount totaling :totalSalary
     */
    public function iSeeThatHasBaseSalaryAndBonusTotaling(string $employeeName, Money $baseSalary, string $bonusType,
        Money $bonusAmount, Money $totalSalary)
    {
        Assert::notEmpty(array_filter(
            $this->webClient->getLatestResponseContent(),
            fn($row) => $row['name'] === $employeeName
                && $row['bonusType'] === $bonusType
                && $row['bonus'] === $bonusAmount->getAmount()
                && $row['totalSalary'] === $totalSalary->getAmount()
        ));
    }

    /**
     * @Transform /^(\d+)$/
     */
    public function castStringToNumber(string $string): int
    {
        return intval($string);
    }

    /**
     * @Transform /^(\$\d+)$/
     */
    public function castStringToDollars(string $string): Money
    {
        return Money::USD(str_replace('$', '', $string));
    }
}
