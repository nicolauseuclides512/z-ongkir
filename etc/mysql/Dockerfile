FROM mysql:5.7
MAINTAINER jee.archer@gmail.com

ADD ./db/* /docker-entrypoint-initdb.d/

COPY ./config/my.cnf /etc/mysql
RUN chmod 0444 /etc/mysql/my.cnf

COPY ./config/docker-entrypoint.sh /usr/local/bin/
RUN ln -sf usr/local/bin/docker-entrypoint.sh /entrypoint.sh # backwards compat