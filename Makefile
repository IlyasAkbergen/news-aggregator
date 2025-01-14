default: setup

setup: up install migration test seed

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
