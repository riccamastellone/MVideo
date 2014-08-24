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

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
ARTISAN="artisan";

echo "MVideo Deploy - $VERSION"

echo -e "GIT PULL ($BRANCH)"
cd $DIR && git pull origin $BRANCH && git submodule update --recursive


echo "DUMP-AUTOLOAD"
composer dump-autoload
php $DIR/$ARTISAN dump-autoload

echo "MIGRATION"

php $DIR/$ARTISAN migrate