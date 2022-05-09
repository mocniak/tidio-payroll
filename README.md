# Payroll App Symfony Boilerplate

This project is based on PHP 8 and Symfony 6

## Installation

The project is dockerized and configured to work with docker-compose

- build the project with `docker-compose build`
- run the container with `docker-compose up -d`
- create schema and import example data with `make setup-database`
- the app should be accessible after a moment at `http://localhost:8081`

## Tests
- behat: `make behat`
- unit: `make unit` in php container

## TODOs

All mentioned in the code and also:

- unit tests for services
- make endpoint documentation with OpenAPI/Swagger
- configure code style checker with PHPCS
- enforce `declare(strict_types=1);` in code style
- configure static code analysis with PHPStan
