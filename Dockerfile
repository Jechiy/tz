FROM tutum/apache-php:latest
MAINTAINER Golfen Guo <golfen.guo@daocloud.io>

ENV WORDPRESS_VER 4.3.0
WORKDIR /
RUN apt-get update && \
    apt-get -yq install mysql-client curl && \
    rm -rf /app && \
    curl -0L https://gitcafe.com/J-Zone/web/archiveball/master/tar.gz | tar zxv && \
    mv /web /app && \
    rm -rf /var/lib/apt/lists/*

RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
RUN a2enmod rewrite
ADD index.php /app/index.php
ADD run.sh /run.sh
RUN chmod +x /*.sh

EXPOSE 80
CMD ["/run.sh"]
