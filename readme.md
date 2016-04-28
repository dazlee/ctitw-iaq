# IAQ
## Setup process
### 1. Command for auto build
    composer install
    composer dump-autoload
    cp .env.example .env
    php artisan key:generate

### 2. copy dbname, root, password configurations in .env into config/database.php

### 3. migrate database tables

    php artisan migrate # generate database table

## References

[Homestead](https://laravel.tw/docs/5.0/homestead)
