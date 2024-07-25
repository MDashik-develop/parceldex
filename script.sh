#!/bin/bash

# Define variables
REPO_PATH=/home/outduprw/elogistics.com.bd
BRANCH=master
GIT_URL=https://github.com/mohasin-dev/parceldex.git
DEPLOY_PATH=/home/outduprw/elogistics.com.bd

# Go to the repo path
cd $REPO_PATH

# Fetch the latest changes from the remote repository
git fetch origin

# Check out the branch you want to deploy
git checkout $BRANCH

# Pull the latest changes
git pull origin $BRANCH

# Install/update composer dependencies
composer install --no-dev --prefer-dist --optimize-autoloader

# Run migrations
#php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Set proper permissions (if necessary)
chown -R user:group $DEPLOY_PATH
chmod -R 755 $DEPLOY_PATH
chmod -R 775 $DEPLOY_PATH/storage
chmod -R 775 $DEPLOY_PATH/bootstrap/cache
