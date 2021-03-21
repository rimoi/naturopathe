## General
.DEFAULT_GOAL := help
help: ## Show the help
	    @grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

# Constants
DOCKER_COMPOSE = docker-compose
IMAGE_NAME = my-project-base

# Environments
ENV_PHP = $(DOCKER_COMPOSE) exec -u symfony web
ENV_ROOT_PHP = $(DOCKER_COMPOSE) exec web
ENV_ENCORE = $(DOCKER_COMPOSE) exec -u symfony encore

# Tools
COMPOSER = $(DOCKER_COMPOSE) run -u symfony web composer
YARN = $(DOCKER_COMPOSE) run -u symfony encore yarn

## Docker managment
.PHONY: build
build: docker-compose.yml ## Build all docker images
	    make build-database
	    make build-encore
	    make build-web

.PHONY: build-database
build-database: docker-compose.yml docker/database/Dockerfile ## Build docker database image
	    $(DOCKER_COMPOSE) build --build-arg UID=$(shell id -u) --build-arg GID=$(shell id -g) --no-cache database

.PHONY: build-encore
build-encore: docker-compose.yml docker/node/Dockerfile ## Build docker encore image
	    $(DOCKER_COMPOSE) build --build-arg UID=$(shell id -u) --build-arg GID=$(shell id -g) --no-cache encore

.PHONY: build-web
build-web: docker-compose.yml docker/apache/Dockerfile docker/apache/Dockerfile.dev ## Build docker web image
	    make build-base-web
	    $(DOCKER_COMPOSE) build --build-arg UID=$(shell id -u) --build-arg GID=$(shell id -g) --no-cache web

.PHONY: build-base-web
build-base-web: docker-compose.yml docker/apache/Dockerfile docker/apache/Dockerfile.dev ## Build docker base web image
	    docker build -t $(IMAGE_NAME):base -f docker/apache/Dockerfile --no-cache docker/apache

.PHONY: clean
clean: docker-compose.yml ## Clean the PHP and JS libraries
	    $(ENV_ROOT_ENV) rm -rf .env ./node_modules ./vendor

.PHONY: create
create: docker-compose.yml ## Build docker images, run the containers, install PHP libraries, create database, load fixtures
	    make build
	    make pinstall
	    make einstall
	    $(DOCKER_COMPOSE) up -d --remove-orphans --force-recreate
	    make create-db
	    make create-schema
	    make load-fixtures

.PHONY: down
down: docker-compose.yml ## Kill the containers
	    $(DOCKER_COMPOSE) down

.PHONY: stop
stop: docker-compose.yml ## Stop the containers
	    $(DOCKER_COMPOSE) stop

.PHONY: up
up: docker-compose.yml ## Start the containers
	    $(DOCKER_COMPOSE) up -d

.PHONY: build-prod
build-prod: docker/apache/Dockerfile docker/apache/Dockerfile.prod ## Build image production
	    make build-base-web
	    docker build -t image:prod-$(shell git rev-parse --short HEAD) -f docker/apache/Dockerfile.prod .
.PHONY: push-prod
push-prod: docker/apache/Dockerfile docker/apache/Dockerfile.prod ## Push image production
	    docker push image:prod-$(shell git rev-parse --short HEAD)

## Database commands
.PHONY: dmysql
dmysql: docker-compose.yml
	    $(DOCKER_COMPOSE) exec database mysql -u symfony -psymfony symfony

## PHP commands
.PHONY: pinstall
pinstall: symfony/composer.json symfony/composer.lock ## Install PHP libaries
	    $(COMPOSER) install

.PHONY: pupdate
pupdate: symfony/composer.json ## Update PHP libraries
	    $(COMPOSER) update

.PHONY: prequire
prequire: symfony/composer.json ## Require a new PHP libary
	    $(COMPOSER) require $(PACKAGE)

.PHONY: prequire-dev
prequire-dev: symfony/composer.json ## Require a new PHP libary for dev
	    $(COMPOSER) require --dev $(PACKAGE)

.PHONY: premove
premove: symfony/composer.json ## Remove a PHP library
	    $(COMPOSER) remove $(PACKAGE)

.PHONY: psh
psh: docker-compose.yml ## Jump into the PHP container
	    $(ENV_PHP) /bin/sh

## Encore commands
.PHONY: einstall
einstall: symfony/package.json ## Install JS/CSS libraries
	    $(YARN)

.PHONY: eupdate
eupdate: symfony/package.json ## Update JS/CSS libraries
	    $(YARN) upgrade

.PHONY: esh
esh: symfony/package.json ## Jump into encore container
	    $(ENV_ENCORE) sh

.PHONY: elog
elog: symfony/package.json ## Show the logs of the encore container
	    $(DOCKER_COMPOSE) logs -f encore

.PHONY: erestart
erestart: symfony/package.json ## Restart the encore container
	    $(DOCKER_COMPOSE) restart encore

## Symfony commands
.PHONY: cache-clear
cache-clear: symfony/var/cache/ ## Purge Symfony cache
	    $(ENV_PHP) rm -rf ./var/cache/*

router: symfony/config/routes/ ## Print router
	    $(ENV_PHP) php bin/console debug:router

## Tools commands
.PHONY: dump
dump: symfony/Makefile ## Check if there is no dump in the code base
	    $(ENV_PHP) make dump

.PHONY: phpcs
phpcs: symfony/Makefile ## Launch php-cs-fixer
	    $(ENV_PHP) make phpcs

.PHONY: phpcs-dry-run
phpcs-dry-run: symfony/Makefile ## Launch php-cs-fixer but no modifications are made
	    $(ENV_PHP) make phpcs-dry-run

.PHONY: phpstan
phpstan: symfony/Makefile ## Launch phpstan verification
	    $(ENV_PHP) make phpstan

.PHONY: phpunit
phpunit: symfony/Makefile ## Lauch phpunit test
	    $(ENV_PHP) make phpunit

## Doctrine commands
.PHONY: create-db
create-db: symfony/bin/console ## Create database if not exists
	    $(ENV_PHP) php bin/console doctrine:database:create --if-not-exists --no-interaction

.PHONY: create-schema
create-schema: symfony/bin/console ## Create schema
	    $(ENV_PHP) php bin/console doctrine:schema:create --no-interaction

.PHONY: drop-db
drop-db: symfony/bin/console ## Drop database if exists
	    $(ENV_PHP) php bin/console doctrine:database:drop --if-exists --force --no-interaction

.PHONY: drop-schema
drop-schema: symfony/bin/console ## Drop schema
	    $(ENV_PHP) php bin/console doctrine:schema:drop --force --no-interaction

.PHONY: load-fixtures
load-fixtures: symfony/bin/console symfony/src/DataFixtures ## Load fixtures
	    $(ENV_PHP) php bin/console doctrine:fixtures:load --no-interaction

.PHONY: migrate
migrate: symfony/bin/console symfony/src/Migrations ## Execute migrations
	    $(ENV_PHP) php bin/console doctrine:migration:migrate
