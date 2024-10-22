#!/usr/bin/env
#For testing purposes only
docker start test-mariadb && \
php bin/console doctrine:database:create --if-not-exists && \
sleep 5 && \
php bin/console --no-interaction doctrine:migrations:migrate && \
sleep 5 && \
symfony server:start && \
watch -g -n 5 'date +%H:%M'
