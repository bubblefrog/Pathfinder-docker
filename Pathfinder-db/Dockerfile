FROM mariadb:latest
ADD https://www.fuzzwork.co.uk/dump/mysql-latest.tar.bz2 /tmp/mysql-latest.tar.bz2
RUN apt-get update && apt-get install -y lbzip2\
&& tar -C /tmp -xf /tmp/mysql-latest.tar.bz2\
&& find /tmp -name "*.sql" |xargs cp -t /docker-entrypoint-initdb.d
ENV MYSQL_DATABASE="pathfinder"