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
