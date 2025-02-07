# Order demo

## Zadani

> Vytvořte jednoduchý REST endpoint pro získání dat objednávky podle jejího jedinečného
> identifikátoru.

`GET /api/order/{orderId}`  
`tests/Api/OrderTest.php`

> Objednávka obsahuje data jako identifikátor, datum vytvoření, název, částku, měnu, stav.

`src/Entity/Order.php`

> Také obsahuje položky objednávky, kdy u každé objednávky je jedna a víc položek. Položka
> objednávky má název a částku. Pokud se vám zdají další data užitečná, klidně rozšiřte.

`src/Entity/OrderItem.php`

> Formát odpovědi je na Vás. Můžete zkusit připravit řešení umožňující podporu s více
> formáty. Zamyslete se nad REST pojmenováním endpointů a návratovými kódy, které se v
> REST používají.

`GET /api/order/1` -> `200`  
`GET /api/order/asasas` -> `404`

> Objednávky se mají načítat z MySQL databáze (není nutné mít funkční DB, klidně jen
> testovací implementaci). Řešení by nemělo zavést větší závislost na DB enginu v celém

`docker-compose.yaml` -> `mariadb`

> kódu. Zkuste připravit řešení umožňující jednoduchou změnu databázového enginu nebo
> celkovou změnu zdroje dat (např. přesun na jiné API).

`migrations/Version20250207133549.php`
`src/Entity/*`

## Fixtures

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
