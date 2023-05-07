#!/bin/bash
cd /var/www/html
php artisan schedule:run --verbose --no-interaction
