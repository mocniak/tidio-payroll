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

    public function __construct(
        WebClient $webClient,
        DepartmentRepository $departmentRepository,
        EmployeeRepository $employeeRepository
    )
    {
        $this->webClient = $webClient;
        $this->departmentRepository = $departmentRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @BeforeScenario
     */
    public function cleanDB($event)
    {
        $this->departmentRepository->removeAll();
        $this->employeeRepository->removeAll();
    }
    /**
     * @Given there is a :departmentName department having :bonusType bonus of :bonusAmount
     */
    public function thereIsADepartmentHavingBonusOf(
        string $departmentName,
        string $bonusType,
        string|Money $bonusAmount
    )
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
     * @When I display payrolls
     */
    public function iDisplayAPayrolls()
    {
        $this->webClient->fetch('/api/payroll');
    }

    /**
     * @When I display payrolls filtered by :filterName being :filterValue
     */
    public function iDisplayAPayrollsWithBeing(string $filterName, string $filterValue)
    {
        $this->webClient->fetch('/api/payroll?filter=' . $filterName . ':' . $filterValue);
    }

    /**
     * @When I display payrolls ordered by :orderKey :direction
     */
    public function iDisplayPayrollsOrderedBy(string $orderKey, string $direction)
    {
        $this->webClient->fetch('/api/payroll?order=' . $orderKey . ':' . $direction);
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
            fn($row) => $row['employeeName'] === $employeeName && $row['departmentName'] === $departmentName
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
            fn($row) => $row['employeeName'] === $employeeName
                && $row['bonusType'] === $bonusType
                && $row['bonusSalary'] === $bonusAmount->getAmount()
                && $row['totalSalary'] === $totalSalary->getAmount()
        ));
    }

    /**
     * @Then I see that :nth result is :employeeName
     */
    public function iSeeThatNthResultIs(int $nth, string $employeeName)
    {
        Assert::eq($this->webClient->getLatestResponseContent()[$nth - 1]['employeeName'], $employeeName);
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
        return Money::USD((int)(str_replace('$', '', $string)) * 100);
    }

    /**
     * @Transform /^(\d+)(st|nd|rd|th)$/
     */
    public function castCardinalToNumber($cardinal, $remainder)
    {
        return intval($cardinal);
    }
}
