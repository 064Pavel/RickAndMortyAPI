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

csfix:
	$(DOCKER_PHP_FPM) $(PHP) -dmemory_limit=-1 vendor/bin/php-cs-fixer fix -vvv --no-ansi

db-shell:
	$(DOCKER_DB) psql -U user -d characters

create-migration:
	$(DOCKER_PHP_FPM) $(PHP) bin/console make:migration

load-fixtures:
	$(DOCKER_PHP_FPM) $(PHP) bin/console doctrine:fixtures:load

sql:
	$(DOCKER_PHP_FPM) $(PHP) dbal:run-sql

dump:
	$(DOCKER_DB) psql -U user characters -f /docker-entrypoint-initdb.d/dump.sql