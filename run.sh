#!/usr/bin/env bash

if [ -z $ENV ];then
    ENV="dev"
fi

echo "-------------------------------------------------"
echo ">>    STARTING ZURAGAN ONGKIR [== $ENV ==]      <<"

#check existence storage, if not exist create it
if [ ! -d ./storage ]; then
    mkdir ./storage
fi

#set default docker-compose file
DC="-f docker-compose.yml"


#reset confirmation
printf "\nDo you wish to reset before start service? (y/N/c)? "
read answer
if echo "$answer" | grep -iq "^y" ;then
    docker-compose down
elif echo "$answer" | grep -iq "^c" ;then
    exit
fi

#build container
docker-compose $DC build

echo "-------------------------------------------------"
echo "+++++++++++++++ START APP CONTAINER +++++++++++++"
docker-compose $DC up -d fpm web

#DB migrate
docker-compose exec fpm php artisan migrate

#show docker log
docker-compose logs -f