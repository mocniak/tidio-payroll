COMPOSE_EXEC=docker-compose \
	exec \
	-e APP_ENV=test \
	payroll-php
BEHAT=vendor/bin/behat
PHPUNIT=vendor/bin/phpunit

.PHONY: behat
behat:
	${COMPOSE_EXEC} ${BEHAT} ${BEHAT_OPTS}

.PHONY: phpunit
phpunit:
	${COMPOSE_EXEC} ${PHPUNIT} ${PHPUNIT_OPTS}

.PHONY: test
test:
	make behat
	make phpunit

.PHONY: create-schema
create-schema:
	docker-compose exec payroll-php bash -c "bin/console doctrine:schema:create --env=dev"
	docker-compose exec payroll-php bash -c "bin/console doctrine:schema:update --env=dev --force"
	docker-compose exec payroll-php bash -c "bin/console doctrine:database:create --env=test"
	docker-compose exec payroll-php bash -c "bin/console doctrine:schema:create --env=test"
	docker-compose exec payroll-php bash -c "bin/console doctrine:schema:update --env=test --force"

.PHONY: import-example-data
import-example-data:
	docker-compose exec payroll-php bash -c "bin/console app:import-example-data"
.PHONY: composer-install
composer-install:
	docker-compose exec payroll-php bash -c "composer install"
.PHONY: setup-app
setup-app:
	make composer-install
	make create-schema
	make import-example-data
