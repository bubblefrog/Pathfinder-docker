FROM ubuntu:artful as builder
RUN apt-get update && apt-get install -y composer wget php-zmq php-curl
ARG VERSION=v1.3.4
ARG URL=https://github.com/exodus4d/pathfinder/archive/${VERSION}.tar.gz
ADD ${URL} /tmp/pf.tar.gz
RUN tar -C /tmp/ -xvf /tmp/pf.tar.gz\
&&rm -f /tmp/pf.tar.gz\
&&mkdir -p /var/www/\
&&mv -v -f /tmp/pathfinder-[0-9].[0-9].[0-9] /var/www/pathfinder/\
&&cd /var/www/pathfinder && composer install

ARG PFWSURL=https://github.com/exodus4d/pathfinder_websocket/archive/v1.1.1.tar.gz
ADD ${PFWSURL} /tmp/pfws.tar.gz
RUN tar -C /tmp/ -xvf /tmp/pfws.tar.gz\
&&rm -f /tmp/pfws.tar.gz\
&&mv -v -f /tmp/pathfinder_websocket-[0-9].[0-9].[0-9] /var/www/pfws/\
&&cd /var/www/pfws && composer install


FROM nginx
LABEL MAINTAINER="fredriksson8@gmail.com"
LABEL version=${VERSION}
RUN apt-get update && apt-get install -y \ 
             cron\
             apache2-utils\
             php7.0-mbstring\
             php-xml\
             php7.0\
             php-redis\
             php7.0-fpm\
             php7.0-zmq\
		     php7.0-curl \
             php7.0-mysql\
             php7.0-gd
ADD https://github.com/kelseyhightower/confd/releases/download/v0.15.0/confd-0.15.0-linux-amd64 /usr/local/bin/confd
COPY --from=builder /var/www/pathfinder /var/www/pathfinder
COPY --from=builder /var/www/pfws /var/www/pfws
COPY assets/bootstrap.php /var/www/pathfinder/bootstrap.php
RUN chown -R www-data:www-data /var/www/pathfinder
WORKDIR /var/www/pathfinder
COPY assets/nginx/h5bp /etc/nginx/h5bp
COPY assets/confd-v1.3.4 /etc/confd
COPY assets/conf.d /conf.d

ENV CONFIG_SERVERNAME="PATHFINDER"\
    CONFIG_CACHE="folder=/tmp/cache/"\
    NGINX_HTTP_PORT="80"\
    NGINX_HTTPS_PORT="443"\
    NGINX_HTTPS_CERT=""\
    NGINX_HTTPS_CERTKEY=""\
    SERVERURL="localhost"\
    PF_NAME="Pathfinder"\
    PF_PRIVATE_LIFETIME="60"\
    PF_PRIVATE_MAXCOUNT="3"\
    PF_PRIVATE_MAXSHARED="10"\
    PF_PRIVATE_MAXSYSTEMS="50"\
    PF_CORPORATION_ID=""\
    PF_CORPORATION_LIFETIME="9999"\
    PF_CORPORATION_MAXCOUNT="5"\
    PF_CORPORATION_MAXSHARED="4"\
    PF_CORPORATION_MAXSYSTEMS="100"\
    PF_ALLIANCE_ID=""\
    PF_ALLIANCE_LIFETIME="9999"\
    PF_ALLIANCE_MAXCOUNT="4"\
    PF_ALLIANCE_MAXSHARED="2"\
    PF_ALLIANCE_MAXSYSTEMS="100"\
    PF_SEARCHDEPTH="9000"\
    PF_SEARCHDEFAULTCOUNT="4"\
    PF_MAXDEFAULTCOUNT="6"\
    PF_SEARCHLIMIT="8"\
    MYSQL_PATHFINDER_DBNAME="pathfinder"\    
    MYSQL_PATHFINDER_HOST=""\
    MYSQL_PATHFINDER_USER=""\
    MYSQL_PATHFINDER_PASSWORD=""\
    MYSQL_UNIVERSE_DBNAME="pathfinder"\  
    MYSQL_UNIVERSE_HOST=""\
    MYSQL_UNIVERSE_USER=""\
    MYSQL_UNIVERSE_PASSWORD=""\
    MYSQL_STATIC_DBNAME="pathfinder-static"\  
    MYSQL_STATIC_HOST=""\
    MYSQL_STATIC_USER=""\
    MYSQL_STATIC_PASSWORD=""\
    SSO_CLIENTID=""\
    SSO_SECRETKEY=""

RUN chmod +x /usr/local/bin/confd && /usr/local/bin/confd -onetime -backend env

RUN rm -f /etc/nginx/conf.d/default.conf \
&&  ln -sf /conf.d/routes.ini /var/www/pathfinder/app/routes.ini \
&&  ln -sf /conf.d/config.ini /var/www/pathfinder/app/config.ini \
&&  ln -sf /conf.d/environment.ini /var/www/pathfinder/app/environment.ini \
&&  ln -sf /conf.d/pathfinder.ini /var/www/pathfinder/app/pathfinder.ini \
&&  ln -sf /conf.d/pathfinder.conf /etc/nginx/conf.d/pathfinder.conf \
&&  ln -sf /conf.d/nginx.conf /etc/nginx/nginx.conf\
&&  ln -sf /conf.d/pf-cron /etc/cron.d/pf-cron\
&&  sed -i 's/max_execution_time = .*/max_execution_time = 600/' /etc/php/7.0/fpm/php.ini\
&&  sed -i 's/.*max_input_vars = .*/max_input_vars = 3000/' /etc/php/7.0/fpm/php.ini\
&&  sed -i 's/html_errors = .*/html_errors = Off/' /etc/php/7.0/fpm/php.ini

COPY assets/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT [ "entrypoint.sh" ]