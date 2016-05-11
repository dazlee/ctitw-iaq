# IAQ
## Setup process
### 1. Command for auto build
    sudo apt-get install mysql-server php5-mysql nginx php5-fpm php5-cli php5-mcrypt git
    curl -sS https://getcomposer.org/installer | php
    cp composer.phar /usr/bin

    composer install
    composer dump-autoload
    cp .env.example .env
    php artisan key:generate

### 2. copy dbname, root, password configurations in .env into config/database.php

### 3. migrate database tables

    php artisan migrate # generate database table

### ps. execute these commands if you are going to reconstruct DB
    composer dump-autoload
    php artisan migrate:refresh
    php artisan db:seed

## References

[Homestead](https://laravel.tw/docs/5.0/homestead)
