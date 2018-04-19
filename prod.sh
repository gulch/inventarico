#!/usr/bin/env bash

# optimize autoload
composer dump-autoload --optimize --classmap-authoritative --no-dev

# Laravel optimizations
php artisan config:cache && php artisan route:cache

# set rights for "www-data" user
chmod -R a-rwx,u+rwX,g+rX . && chown www-data:www-data -R .