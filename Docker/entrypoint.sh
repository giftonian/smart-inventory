#!/bin/bash

# check the installation (with existence of a file autoload.php) of composer
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV" # value from docker-compose.yml
    cp .env.example .env
else
    echo "env file exists"
fi



# mysql -u root --password=waqardar<<MYSQL_SCRIPT
# CREATE DATABASE IF NOT EXISTS shop_db;
# CREATE USER IF NOT EXISTS 'shop_user'@'%' IDENTIFIED BY 'shop_pass%$321';
# GRANT ALL PRIVILEGES ON shop_db.* TO 'shop_user'@'%';
# FLUSH PRIVILEGES;
# MYSQL_SCRIPT

# echo "Mysql DB / User creation script ends here."

php artisan migrate
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan route:clear


php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"
