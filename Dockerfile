FROM tutum/apache-php:latest
MAINTAINER Jechiy <773372347@qq.com>
WORKDIR /
RUN apt-get update && \
    apt-get -yq install mysql-client curl && \
    rm -rf /app && \
    rm -rf /var/lib/apt/lists/*

RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
RUN a2enmod rewrite
ADD tz.php /app && \
ADD run.sh /run.sh
RUN chmod +x /*.sh

EXPOSE 80
CMD ["/run.sh"]
