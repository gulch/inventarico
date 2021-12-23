#!/usr/bin/env bash

# Laravel optimizations
php artisan view:clear
php artisan route:clear
php artisan clear-compiled
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Dump autoload
composer dump-autoload --optimize --classmap-authoritative --no-dev

# set rights for "www-data" user
chmod -R a-rwx,u+rwX,g+rX . && chown www-data:www-data -R .
