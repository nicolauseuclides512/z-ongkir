# Set the base image to Ubuntu
FROM ubuntu:16.04

MAINTAINER Jehan Afwazi A <jehan@ontelstudio.com>

RUN apt-get clean && apt-get -y update && apt-get install -y locales curl software-properties-common git \
  && locale-gen en_US.UTF-8

# Install nginx
RUN apt-get update && apt-get install -y --fix-missing nginx curl supervisor git zip libxrender1 wget

# Copy a configuration file from the current directory
COPY config/default /etc/nginx/sites-available/default

# Append "daemon off;" to the configuration file
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

# copy config sh
ADD config/run.sh /

RUN chmod +x /run.sh

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
# running config

CMD /run.sh

