PHP := php
COMPOSER := composer
DOCKER_PHP_FPM := docker compose exec php-fpm
DOCKER_DB := docker compose exec database

up:
	docker compose up -d --remove-orphans

down:
	docker compose down

diff:
	$(DOCKER_PHP_FPM) $(PHP) bin/console doctrine:migrations:diff

migrate:
	$(DOCKER_PHP_FPM) $(PHP) bin/console doctrine:migrations:migrate

composer:
	$(DOCKER_PHP_FPM) $(COMPOSER) install

php-shell:
	$(DOCKER_PHP_FPM) sh

db-shell:
	$(DOCKER_DB) psql -U app -d app

create-migration:
	$(DOCKER_PHP_FPM) $(PHP) bin/console make:migration

load-fixtures:
	$(DOCKER_PHP_FPM) $(PHP) bin/console doctrine:fixtures:load