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

###> Doctrine ###

check-reload-db:
	@echo "\033[92mAre you sure that you want to reload the database?\033[0m [y/N] " && read ans && [ $${ans:-N} = y ]

migrations-status:
	$(CONSOLE) doctrine:migrations:list

reload-db: check-reload-db
	$(CONSOLE) doctrine:database:drop --force
	$(MAKE) -f $(THIS_FILE) create-db

migrate:
	$(CONSOLE) doctrine:migrations:migrate latest

migrate-prev:
	$(CONSOLE) doctrine:migrations:migrate prev

migrations-diff:
	$(CONSOLE) doctrine:migrations:diff

create-db:
	$(CONSOLE) doctrine:database:create

create-db-test:
	$(CONSOLE) doctrine:database:create --env=test

reload-db-test: check-reload-db
	$(CONSOLE) doctrine:database:drop --force --env=test
	$(CONSOLE) doctrine:database:create --env=test
	$(CONSOLE) doctrine:schema:create --env=test


###< Doctrine ###