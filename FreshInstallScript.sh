#!/usr/bin/env bash
##
# after clone project
# 1. copy file `.env.example` trus direname jadi `.env`
# 2. jalankan `./composer.phar install`
# 3. jalankan migration & seeder
# 4. jalankan server ke port 8789
##

mkdir ./database/data && touch ./database/data/zuragan_ongkir_db.sqlite
cp ./.env.example ./.env
./composer.phar install
php artisan migrate
php artisan db:seed --class=AssetTableSeeder
php artisan serv --port=8789

