PHP := php
CONSOLE := $(PHP) bin/console
PHPUNIT := $(PHP) bin/phpunit
THIS_FILE := $(lastword $(MAKEFILE_LIST))

docker-up:
	docker-compose $(COMPOSE_LOCAL_FILES) up --build -d

docker-exec-php:
	docker-compose $(COMPOSE_LOCAL_FILES) exec php bash

coverage:
	$(PHPUNIT) --coverage-html public/testing