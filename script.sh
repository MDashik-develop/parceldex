#!/bin/bash

# Define variables
REPO_PATH=/home/outduprw/elogistics.com.bd
BRANCH=master
GIT_URL=https://github.com/mohasin-dev/parceldex.git
DEPLOY_PATH=/home/outduprw/elogistics.com.bd

# Go to the repo path
cd $REPO_PATH || { echo "Failed to change directory to $REPO_PATH"; exit 1; }

# Fetch the latest changes from the remote repository
git fetch origin || { echo "Failed to fetch from origin"; exit 1; }

# Check out the branch you want to deploy
git checkout $BRANCH || { echo "Failed to checkout branch $BRANCH"; exit 1; }

# Pull the latest changes
git pull origin $BRANCH || { echo "Failed to pull from origin"; exit 1; }

# Install/update composer dependencies
composer install --no-dev --prefer-dist --optimize-autoloader || { echo "Composer install failed"; exit 1; }

# Run migrations (optional)
#php artisan migrate --force || { echo "Migration failed"; exit 1; }

# Clear caches
php artisan cache:clear || { echo "Failed to clear cache"; exit 1; }
php artisan config:clear || { echo "Failed to clear config"; exit 1; }
php artisan route:clear || { echo "Failed to clear route"; exit 1; }
php artisan view:clear || { echo "Failed to clear view"; exit 1; }

# Set proper permissions (if necessary)
chown -R user:group $DEPLOY_PATH || { echo "Failed to change ownership"; exit 1; }
chmod -R 755 $DEPLOY_PATH || { echo "Failed to change permissions"; exit 1; }
chmod -R 775 $DEPLOY_PATH/storage || { echo "Failed to change permissions for storage"; exit 1; }
chmod -R 775 $DEPLOY_PATH/bootstrap/cache || { echo "Failed to change permissions for cache"; exit 1; }

