#!/bin/bash

# Variables
APP_DIR=/var/www/documentinghateinthe.us
TITLE="Documenting Hate"

# Go to app directory
cd $APP_DIR

# Enter Maintenance Mode
php artisan down --message='$TITLE will be back in a minute. Please retry then.' --retry=60

# Get code chagnes
git pull origin master

# Install composer packages and optimize
composer install
composer dump-autoload --optimize

# Clear old Laravel stuff out
php artisan clear-compiled
php artisan view:clear
php artisan cache:clear_view_objects

# Optimize 
php artisan optimize
php artisan config:cache
php artisan route:cache

# Exit Maintenance Mode
php artisan up
