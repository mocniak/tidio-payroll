# Payroll App Symfony Boilerplate

This project is based on PHP 8 and Symfony 6

## Installation

The project is dockerized and configured to work with docker-compose

- build the project with `docker-compose build`
- run the container with `docker-compose up -d`
- get to the container with `docker-compose exec payroll-php bash`
- the app should be accessible after a moment at `http://localhost:8081`

## Tests
- behat: `APP_ENV=test vendor/bin/behat` in php container
- unit: `vendor/bin/phpunit` in php container

## TODOs

All mentioned in the code and also:

- unit tests for services
- make endpoint documentation with OpenAPI/Swagger
- configure code style checker with PHPCS
- enforce `declare(strict_types=1);` in code style
- configure static code analysis with PHPStan
