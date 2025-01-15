default: setup

setup: copy-env up install migration seed test

copy-env:
	cp .env.example .env

install:
	docker compose run --rm php sh -c "composer install"

migration:
	docker compose run --rm php sh -c "php artisan migrate"

seed:
	docker compose run --rm php sh -c "php artisan db:seed"

up:
	docker compose up -d

test:
	docker compose run --no-deps --rm php sh -c "touch database/database.sqlite && composer install && composer test"

phpstan:
	docker compose run --no-deps --rm php sh -c "composer install && composer phpstan"

fetch_articles:
	docker compose run --no-deps --rm php sh -c "php artisan articles:fetch"
