#!/bin/bash
if [ -z ${NO_TEMPLATE}]
then
    /usr/local/bin/confd -onetime -backend env 
fi
if [ -f "/conf.d/bootstrap"]
then
    echo "No bootstraping for you\n"
else
    cd /var/www/pathfinder
    php bootstrap.php
    echo "" > /conf.d/bootstrap
fi
htpasswd -b -c /etc/nginx/.setup_pass admin ${ADMIN_PASSWORD}
service cron start
service php7.0-fpm start
service nginx start
cd /var/www/pfws && php /var/www/pfws/cmd.php

exec $@