#!/bin/bash
. `pwd`/.env
if [ ! -d "vendor" ]; then
    composer global require hirak/prestissimo
    export COMPOSER_ALLOW_SUPERUSER=1
    composer install
    chown -R www-data:www-data storage/ bootstrap/cache
fi
php .docker/php7/wait-for-mysql.php
php vendor/bin/phinx migrate
echo "+---------+"
echo "| Welcome |"
echo "+---------+"
echo "App URL: ${APP_URL}"
php-fpm
