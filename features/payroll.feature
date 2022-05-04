Feature:
    In order to pay wage for our corporation employees
    As a capitalist
    I want to see remuneration report

    Scenario: It shows a payrolls of all the employees
        Given there is a "HR" department having "longevity" bonus of "$100"
        And there is a "Customer Service" department having "percentage" bonus of "10%"
        And there is an employee "Adam Kowalski" with base salary "$1000" working in "HR" department for 15 years
        And there is an employee "Ania Nowak" with base salary "$1100" working in "Customer Service" department for 5 years
        When I display a payrolls
        Then I see 2 results
        And I see that "Adam Kowalski" is working in "HR" department
        And I see that "Adam Kowalski" has base salary "$1000" and "longevity" bonus "$1000" totaling "$2000"
        And I see that "Ania Nowak" is working in "Customer Service" department
        And I see that "Ania Nowak" has base salary "$1100" and "percentage" bonus "$110" totaling "$1210"
