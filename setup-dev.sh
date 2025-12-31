#!/bin/bash

cd symfony-app
sudo rm .env.local.php
sudo rm -fr public/assets
sudo rm -fr var/cache/dev
sudo rm -fr var/cache/prod
composer install
php bin/console cache:clear --env=dev
