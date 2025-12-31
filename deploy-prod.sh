#!/bin/bash

git pull
cd symfony-app
composer dump-env prod
composer install --no-dev --optimize-autoloader
php bin/console asset-map:compile
php bin/console cache:clear --env=prod
