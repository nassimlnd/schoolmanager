YELLOW=\033[33m
GREEN=\033[32m
BLUE=\033[34m
CYAN=\033[36m
RESET=\033[0m

.PHONY: up down cache-clear update-db create-db drop-db migration entity fixtures logs install cmd

PHP_CONTAINER_NAME := php
NODE_CONTAINER_NAME := node
DB_CONTAINER_NAME := db

-include .env
-include .env.local

PHP_CONTAINER_RUNNING := $(shell docker ps -q -f name=${PHP_CONTAINER_NAME})

ifeq ($(PHP_CONTAINER_RUNNING),)
	dp := docker compose run --rm ${PHP_CONTAINER_NAME}
else
	dp := docker compose exec ${PHP_CONTAINER_NAME}
endif

NODE_CONTAINER_RUNNING := $(shell docker ps -q -f name=${NODE_CONTAINER_NAME})

ifeq ($(NODE_CONTAINER_RUNNING),)
	dn := docker compose run --rm ${NODE_CONTAINER_NAME}
else
	dn := docker compose exec ${NODE_CONTAINER_NAME}
endif

DB_CONTAINER_RUNNING := $(shell docker ps -q -f name=${DB_CONTAINER_NAME})

ifeq ($(DB_CONTAINER_RUNNING),)
	dd := docker compose run --rm ${DB_CONTAINER_NAME}
else
	dd := docker compose exec ${DB_CONTAINER_NAME}
endif

help:
	@echo "$(BLUE)Liste des commandes disponibles :$(RESET)"
	@awk 'BEGIN {FS = ":.*##"} \
		/^##-/ { printf "\n$(CYAN)--- %s --- $(RESET)\n", substr($$0, 5) } \
		/^[a-zA-Z_-]+:.*?##/ { printf "  $(YELLOW)%-15s$(GREEN)%s$(RESET)\n", $$1, $$2 }' $(MAKEFILE_LIST)
	@echo ""

##- Gestion du projet
.env: .env.dist ## Create .env file and generate APP_SECRET
	@cp $< $@
	@if grep -q "^APP_SECRET=" .env; then \
		NEW_SECRET=$$(openssl rand -hex 16); \
		sed -i "s/^APP_SECRET=.*/APP_SECRET=$$NEW_SECRET/" .env; \
	else \
		echo "APP_SECRET=$$(openssl rand -hex 16)" >> .env; \
	fi; \

docker-compose.override.yaml: docker-compose.override.yaml.dist ## Create compose.override.yaml file
	@cp $< $@

up: install assets-install ## Start the project
	@docker compose up -d --remove-orphans
	@$(MAKE) --no-print-directory cache-clear
	@$(MAKE) --no-print-directory assets-watch

kill: ## Kill the project
	@docker compose kill

down: ## Stop the project
	@docker compose down --remove-orphans

restart: ## Restart the project
	$(MAKE) --no-print-directory down
	$(MAKE) --no-print-directory up

install: .env docker-compose.override.yaml ## Install the project
	@$(dp) composer install

##- Gestion des bases de données

create-db: ## Create the database
	@$(dp) bin/console doctrine:database:create --if-not-exists

drop-db: ## Drop the database
	@$(dp) bin/console doctrine:database:drop --force --if-exists

migration: create-db ## Create a new migration
	@$(dp) bin/console make:migration

update-db: create-db ## Update the database schema
	@$(dp) bin/console doctrine:migrations:migrate --no-interaction

entity: ## Create a new entity
	@docker compose exec -it ${PHP_CONTAINER_NAME} bin/console make:entity

fixtures: update-db ## Load fixtures
	@$(dp) bin/console doctrine:fixtures:load --no-interaction -vv

##- Gestion des assets

assets-install: ## Install assets
	@$(dn) npm install

assets-watch: ## Build assets in dev mode
	@$(dn) npm run dev-server

assets-build: ## Build assets in prod mode
	@$(dn) npm run build

##- Gestion des tests

test-create-db: ## Create the test database
	@$(dp) bin/console --env=test doctrine:database:create --if-not-exists

test-drop-db: ## Drop the test database
	@$(dp) bin/console --env=test doctrine:database:drop --force --if-exists

test-update-db: test-create-db ## Update the test database schema
	@$(dp) bin/console --env=test doctrine:migrations:migrate --no-interaction

test-fixtures: update-db ## Load test fixtures
	@$(dp) bin/console --env=test doctrine:fixtures:load --no-interaction -vv

test: test-update-db ## Run tests
	@$(dp) bin/phpunit

create-test: ## Create a new test
	@$(dp) bin/console make:test

##- Commandes utiles

cache-clear: ## Clear the symfony cache
	@$(dp) bin/console cache:clear

logs: ## Display logs
	@docker compose logs -f

php-cmd: ## Open a bash session in the php container
	@docker compose exec -it ${PHP_CONTAINER_NAME} bash

node-cmd: ## Open a bash session in the node container
	@docker compose exec -it ${NODE_CONTAINER_NAME} sh

db-cmd: ## Open a bash session in the db container
	@docker compose exec -it ${DB_CONTAINER_NAME} sh

cmd: ## Open a bash session in the php (p) / node (n) / postgres (d) container
	@echo "What container do you want to open a session in (p/n/d) ?"
	@read -r CONTAINER; \
	if [ $$CONTAINER = "p" ]; then \
		$(MAKE) --no-print-directory php-cmd; \
	elif [ $$CONTAINER = "n" ]; then \
		$(MAKE) --no-print-directory node-cmd; \
	elif [ $$CONTAINER = "d" ]; then \
		$(MAKE) --no-print-directory db-cmd; \
	else \
		echo "Invalid choice"; \
	fi

clear: ## Clearing ignored files
	sudo rm -rf var public/build .env docker-compose.override.yaml .phpunit.result.cache

full-clear: ## Clearing all files
	sudo rm -rf var public/build .env docker-compose.override.yaml .phpunit.result.cache vendor node_modules