<?php

namespace App\View;

enum PayrollFilterType: string
{
    case EMPLOYEE_NAME = 'employeeName';
    case DEPARTMENT = 'departmentName';
}
