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
 	docker-compose exec payroll-php bash -c "bin/console app:import-example-data"
