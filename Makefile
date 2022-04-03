DOCKER_COMPOSE = docker-compose
NODE_RUN = $(DOCKER_COMPOSE) run -u node --rm -e YARN_REGISTRY -e PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=1 -e PUPPETEER_EXECUTABLE_PATH=/usr/bin/google-chrome node
YARN_RUN = $(NODE_RUN) yarn
API_RUN = $(DOCKER_COMPOSE) run --rm --no-deps api
API_EXEC = $(DOCKER_COMPOSE) exec --rm --no-deps api

.DEFAULT_GOAL := help

.PHONY: help
help:
	@echo "help"

# Api 

.PHONY: vendor
vendor:
	$(API_RUN) composer install

.PHONY: cache
cache:
	$(API_RUN) rm -rf var/cache && $(API_RUN) bin/console cache:warmup

.PHONY: cache-clear
cache-clear:
	$(API_RUN) bin/console cache:clear

.PHONY: test
test:
	APP_ENV=test $(MAKE) up
	APP_ENV=test $(MAKE) unit-test


.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d --remove-orphans ${CONTAINERS}

.PHONY: down
down:
	$(DOCKER_COMPOSE) down -v

.PHONY: behat-test
behat-test:
	$(DOCKER_COMPOSE) run api vendor/bin/behat


.PHONY: unit-test
unit-test:
	$(DOCKER_COMPOSE) run api bin/phpunit
