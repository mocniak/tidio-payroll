<?php

namespace App\View;

enum PayrollOrderType: string
{
    case EMPLOYEE_NAME = 'employeeName';
    case DEPARTMENT = 'departmentName';
    case BASE_SALARY = 'baseSalary';
    case BONUS_SALARY = 'bonusSalary';
    case BONUS_TYPE = 'bonusType';
    case TOTAL_SALARY = 'totalSalary';
}

