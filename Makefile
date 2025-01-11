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
	docker compose run --no-deps --rm php sh -c "composer install && php /var/www/html/vendor/phpunit/phpunit/phpunit --no-configuration /var/www/html/tests/Unit"

phpstan:
	docker compose run --no-deps --rm php sh -c "composer install && php /var/www/html/vendor/bin/phpstan"
