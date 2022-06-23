#!/usr/bin/env bash

# Laravel optimizations
# clear
php artisan view:clear
php artisan route:clear
php artisan clear-compiled
# cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
# php artisan queue:restart

# Dump autoload
composer dump-autoload --optimize --classmap-authoritative --no-dev

# set rights for "www-data" user
chmod -R a-rwx,u+rwX,g+rX . && chown www-data:www-data -R .
