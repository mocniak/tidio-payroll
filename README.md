# Payroll App Symfony

This project is based on PHP 8.1 and Symfony 6

## Installation

The project is dockerized and configured to work with docker-compose

- build the project with `docker-compose build`
- run the container with `docker-compose up -d`
- install dependencies, create schema and import example data with `make setup-app`
- the app should be accessible after a moment at `http://localhost:8081`

## Usage:

- payrolls are available under the url `http://localhost:8081/api/payroll`. 
- filter example: `http://localhost:8081/api/payroll?filter=departmentName:HR`
- order example: `http://localhost:8081/api/payroll?order=totalSalary:DESC`
- or both `http://localhost:8081/api/payroll?filter=departmentName:IT&order=employeeName:ASC`

## Tests
- behat: `make behat`
- unit: `make unit`

## TODOs and shortcuts

All mentioned in the code and also:

- Employee class has only one name field (instead of fist and last name separately)
- serialized Bonus in Department class (doctrine doesn't support embedded interfaces)
- in memory ordering in DbalPayrolls (not all fields can be ordered with DB engine, so I didn't opitmise there)
- not-very-smart directory structure in /src (I went with default symfony one for now)
- make endpoint documentation with OpenAPI/Swagger
- configure code style checker with PHPCS
- enforce `declare(strict_types=1);` in code style
- configure static code analysis with PHPStan
- mutation tests
