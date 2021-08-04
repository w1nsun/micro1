
compose := docker-compose -f ./.docker/docker-compose.yml

up:
	$(compose) up -d
	$(compose) exec -T micro1_fpm sh -c "composer install"

prepare-local:
	$(compose) exec -T micro1_fpm sh -c "bin/console --env=dev doctrine:database:create --if-not-exists"
	$(compose) exec -T micro1_fpm sh -c "bin/console --env=dev doctrine:schema:update --force"

stop:
	$(compose) stop

cs-fix:
	$(compose) exec -T micro1_fpm sh -c "composer run-script cs-fix"

prepare-tests:
	$(compose) exec -T micro1_fpm sh -c "bin/console --env=test doctrine:database:create --if-not-exists"
	$(compose) exec -T micro1_fpm sh -c "bin/console --env=test doctrine:schema:update --force"

run-tests:
	$(compose) exec -T micro1_fpm sh -c "vendor/bin/phpunit"

exec-php:
	$(compose) exec micro1_fpm sh
