# Order demo

## Install

```shell
bin/console doctrine:fixtures:load -n
```

## API docs

http://localhost:8000/api

## Tests

```shell
APP_ENV=test bin/console doctrine:database:create
APP_ENV=test bin/console doctrine:migrations:migrate -n
composer test
```

## Xdebug

```shell
export PHP_IDE_CONFIG="serverName=php-docker.local"

php -d xdebug.mode=debug -d xdebug.start_with_request=yes bin/console ...
```
