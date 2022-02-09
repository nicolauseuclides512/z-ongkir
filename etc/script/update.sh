#!/usr/bin/env bash

echo ">> update area seed"
docker-compose exec fpm php artisan db:seed --class=UpdateAreaAssetsSeeder