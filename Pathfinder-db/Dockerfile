FROM mariadb:latest
ADD https://github.com/exodus4d/pathfinder/blob/master/export/sql/eve_universe.sql.zip?raw=true /tmp/eve_universe.sql.zip
RUN apt-get update && apt-get install -y lbzip2 unzip\
&& ls -la\
&& unzip /tmp/eve_universe.sql\
&& ls -la\
#&& find /tmp -name "*.sql"\
&& cp eve_universe.sql /docker-entrypoint-initdb.d
#&& find /tmp -name "*.sql" |xargs cp -t /docker-entrypoint-initdb.d
ENV MYSQL_DATABASE="pathfinder"
