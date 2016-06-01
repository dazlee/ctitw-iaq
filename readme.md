# IAQ
## Setup process
### 1. Command for auto build
    sudo apt-get install mysql-server php5-mysql nginx php5-fpm php5-cli php5-mcrypt git
    curl -sS https://getcomposer.org/installer | php
    cp composer.phar  /usr/bin/composer

    composer install
    composer dump-autoload
    cp .env.example .env
    php artisan key:generate

### 2. copy dbname, root, password configurations in .env into config/database.php

### 3. migrate database tables

    php artisan migrate # generate database table

### 4. install memcached
    sudo apt-get install -y memcached php5-memcached
    sudo service <apache2/nginx> restart

### ps. execute these commands if you are going to reconstruct DB
    composer dump-autoload
    php artisan migrate:refresh
    php artisan db:seed

    // 一行搞定
    php artisan migrate:refresh --seed

### constructions
    php artisan make:seeder UserRolesSeeder

## Trouble Shooting
    [BadMethodCallException]
    This cache store does not support tagging.
According to [Cache](https://laravel.com/docs/5.2/cache#cache-tags), file and database drivers do not support cache tags. Need to change following in .env

    CACHE_DRIVER=memcached

    [Class name validation .... (bug from entrust)]
    goto:
    vendor/zizaco/entrust/src/Entrust/Traits/EntrustRoleTrait.php:51
    from:
    this->belongsToMany(Config::get('auth.model'),...
    to:
    this->belongsToMany(Config::get('auth.providers.users.model'),...

## Installing Entrust
User.php can use Authenticatable just fine, no need to change the extension to Eloquent. (Maybe that's the settings from previous version of Laravel.)

## References

[Homestead](https://laravel.tw/docs/5.0/homestead)
