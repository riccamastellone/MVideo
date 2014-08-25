#! /bin/bash

# Utilizziamo questo script per fare il deploy di MVideo

VERSION="0.1";

# Definiamo il branch 

if [ "$ENV" = "dev" ]; then
    BRANCH="dev";
else
    BRANCH="master";
fi

# Se ci viene passato un parametro, lo usamo come branch forzato
if [ -n "$1" ]; then
    BRANCH=$1;
fi

DIR="/var/www/mvideo"
ARTISAN="artisan";

echo "MVideo Deploy - $VERSION"

echo -e "GIT PULL ($BRANCH)"
git pull origin $BRANCH && git submodule update --recursive

rsync -avz ~/MVideo/* /var/www/mvideo

echo "DUMP-AUTOLOAD"
cd $DIR && composer dump-autoload
php $DIR/$ARTISAN dump-autoload

echo "MIGRATION"

php $DIR/$ARTISAN migrate