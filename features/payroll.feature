Feature:
    In order to pay wages for corporate employees
    As a capitalist
    I want to see their pesky remuneration

    Background:
        Given there is a "HR" department having "longevity" bonus of "$100"
        And there is a "Customer Service" department having "percentage" bonus of "10%"
        And there is an employee "Adam Kowalski" with base salary "$1000" working in "HR" department for 15 years
        And there is an employee "Bogdan Wiśniewski" with base salary "$900" working in "Customer Service" department for 2 years
        And there is an employee "Ania Nowak" with base salary "$1100" working in "Customer Service" department for 5 years

    Scenario: It shows a payrolls of all the employees
        When I display payrolls
        Then I see 3 results
        And I see that "Adam Kowalski" is working in "HR" department
        And I see that "Adam Kowalski" has base salary "$1000" and "longevity" bonus "$1000" totaling "$2000"
        And I see that "Ania Nowak" is working in "Customer Service" department
        And I see that "Ania Nowak" has base salary "$1100" and "percentage" bonus "$110" totaling "$1210"
        And I see that "Bogdan Wiśniewski" is working in "Customer Service" department
        And I see that "Bogdan Wiśniewski" has base salary "$900" and "percentage" bonus "$90" totaling "$990"

    Scenario: It can be filtered by the employee name
        When I display payrolls filtered by "employeeName" being "Adam Kowalski"
        Then I see 1 results
        And I see that "Adam Kowalski" is working in "HR" department

    Scenario: It can be filtered by the department name
        When I display payrolls filtered by "departmentName" being "Customer Service"
        Then I see 2 results
        And I see that "Ania Nowak" is working in "Customer Service" department
        And I see that "Bogdan Wiśniewski" is working in "Customer Service" department

    Scenario: It can be ordered by the employee name ascending
        When I display payrolls ordered by "employeeName" "ASC"
        Then I see 3 results
        And I see that 1st result is "Adam Kowalski"
        And I see that 2nd result is "Ania Nowak"
        And I see that 3rd result is "Bogdan Wiśniewski"

    Scenario: It can be ordered by the employee name descending
        When I display payrolls ordered by "employeeName" "DESC"
        Then I see 3 results
        And I see that 1st result is "Bogdan Wiśniewski"
        And I see that 2nd result is "Ania Nowak"
        And I see that 3rd result is "Adam Kowalski"

    Scenario: It can be ordered by the base salary ascending
        When I display payrolls ordered by "baseSalary" "ASC"
        Then I see 3 results
        And I see that 1st result is "Bogdan Wiśniewski"
        And I see that 2nd result is "Adam Kowalski"
        And I see that 3rd result is "Ania Nowak"

    Scenario: It can be ordered by the base salary descending
        When I display payrolls ordered by "baseSalary" "DESC"
        Then I see 3 results
        And I see that 1st result is "Ania Nowak"
        And I see that 2nd result is "Adam Kowalski"
        And I see that 3rd result is "Bogdan Wiśniewski"
