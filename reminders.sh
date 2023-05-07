#!/bin/bash
cd /var/www/html
php artisan schedule:work --verbose --no-interaction

