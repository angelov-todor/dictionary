#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/doctrine' ]; then
	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
		composer app:cache-warmup
		chown -R www-data var
#		bin/doctrine migrations:migrate
	fi

fi

exec docker-php-entrypoint "$@"
