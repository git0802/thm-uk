# The Hot Meal
Rebuilding of the www.thehotmeal.com website.

## Requirements
- PHP 7.2+
- MySQL 8+
- Redis (to increase performance)

## Development installation
### Docker
First, install Docker for your system. Then go to the [laradock website](https://laradock.io/) and follow installation instructions.
### Vagrant
Laravel has a package called Homestead. Visit [official documentation page](https://laravel.com/docs/master/homestead).

## Environment file
```
APP_NAME="The Hot Meal"
# Environments: local, production
APP_ENV=local
APP_KEY=""
# Change to false if in production
APP_DEBUG=true
APP_URL=https://thehotmeal.test

ADMIN_EMAIL=admin@thehotmeal.com

# Local domains: "localhost,127.0.0.1,127.0.0.1:8000,::1,thehotmeal.test,dashboard.thehotmeal.test"
SANCTUM_STATEFUL_DOMAINS="thehotmeal.com,dashboard.thehotmeal.test"
SESSION_DOMAIN=thehotmeal.test
DASHBOARD_DOMAIN=dashboard.thehotmeal.test

STRIPE_API_KEY="your API key"

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

## Installation
Install project dependencies with the [Composer](https://getcomposer.org/):

`composer install`

Generate application key:

`php artisan key:generate`

Start migrations and seeds. Make sure you have a "production" value in an APP_ENV in your .env file to avoid seeding fake data for development needs.

`php artisan migrate --seed`

Create admin and default product.

`php artisan install`

Then install dependencies for the front end:

`npm i`

Compile your front end files with:

`npm run prod`

Run compression command

`npm run compress`

this will create `.gz` and `.br` archives for css and js assets in public directory which will served by nginx in case if `Accept-Encoding: gzip` presents

## Updating
Nothing to update at the moment!


## Nginx requirements

This two lines need to be presented at nginx config for serving compressed assets
```
# Enable static gzip
gzip_static on;
gunzip on;
```

## CRON Setup

```
# create the following cron setup
* * * * * sudo -u www-data /usr/bin/php /var/www/thehotmeal.com/artisan schedule:run >> /dev/null 2>&1
```
