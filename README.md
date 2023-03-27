# wwz shop

## About WWZ

wwz is built with php and laravel. wwz is a ecommerce. wwz is suitable for developers who want to learn more about Laravel and know how to use technologies.

### Installation With Docker

```sh
git clone https://github.com/Hilsonxhero/wwz

# set environment
copy .env.example .env

# install composer
composer install

# Start docker in os
./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan storage:link

./vendor/bin/sail artisan passport:install

./vendor/bin/sail artisan migrate

./vendor/bin/sail artisan module:seed

./vendor/bin/sail artisan queue:work

./vendor/bin/sail artisan schedule:work

```

## Tests

```sh
./vendor/bin/sail artisan test
```

## Fake data

```sh
./vendor/bin/sail artisan module:seed
```

## Elasticsearch

If you use elasticsearch,you must create index.

```sh
./vendor/bin/sail artisan scout:index products

./vendor/bin/sail artisan scout:import "\Modules\Product\Entities\Product"

```

and then you can see other information in kibana

```sh
http://localhost:5601
```

### Infrastructure Description

-   This project is built with laravel and php
-   This project offers a special api for shop
-   [laravel 10 with php 8.1]
-   Docker is used for containers
-   Rabbitmq is used for queues
-   Redis is used for several features
-   Octane is used for optimizing

## Database Description

#### Laravel is flexible in determining database but i performed

-   Mysql for main database
-   Redis for cache database
-   Elasticsearch for search engine

## Webserver Description

-   Octane

## Other Description

-   Use Kibana for dashboard and management elastic
-   Has continuous integration(GitHub actions)

## Pull Requests

Thank you for investing your time in contributing to our project.
