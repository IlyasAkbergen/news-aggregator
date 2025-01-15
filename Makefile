default: setup

setup: copy-env up install migration seed test up-workers

copy-env:
	cp .env.example .env

install:
	docker compose exec php sh -c "composer install"

migration:
	docker compose exec php sh -c "php artisan migrate"

seed:
	docker compose exec php sh -c "php artisan db:seed"

up:
	docker compose up -d

up-php:
	docker compose up --no-deps -d php

up-workers:
	docker compose up -d queue-worker cron

test:
	docker compose exec php sh -c "touch database/database.sqlite && composer install && composer test"

phpstan:
	docker compose exec php sh -c "composer install && composer phpstan"

fetch_articles:
	docker compose exec php sh -c "php artisan articles:fetch"
