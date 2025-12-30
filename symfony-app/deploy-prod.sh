composer install --no-dev --optimize-autoloader
composer dump-env prod
php bin/console asset-map:compile
php bin/console cache:clear --env=prod
