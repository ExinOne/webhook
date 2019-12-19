#!/usr/bin/env bash

rm build.7z

composer update --no-dev --optimize-autoloader

rm public/storage

php artisan cache:clear
php artisan config:clear
php artisan view:clear

7z a -t7z -pTtRcHbByYcZVixAvhaPLrCbZUyUiu8pUY2cPVnMxqhowrm2u6bq2vJPggGFGHfQQ build.7z app/ routes/ artisan bootstrap/ config/ public/ resources/ vendor/ database/ .env.beta .env.release composer.json '-x!public/js/*.map'

php artisan storage:link

composer update
