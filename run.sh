#!/usr/bin/env
#For testing purposes only
docker start test-mariadb && \
echo "Add extra 5 seconds for MariaDB to initialize" && \
sleep 5 && \
php bin/console doctrine:database:create --if-not-exists && \
php bin/console --no-interaction doctrine:migrations:migrate && \
symfony server:start && \
watch -g -n 5 'date +%H:%M'
