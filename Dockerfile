FROM tutum/apache-php:latest
MAINTAINER Jechiy <773372347@qq.com>
WORKDIR /
RUN apt-get update && \
    apt-get -yq install mysql-client curl && \
    rm -rf /app && \
    curl -0L https://dn-downfile.qbox.me/html5-mario.tar.gz | tar zxv && \
    mv /blog /app && \
    rm -rf /var/lib/apt/lists/*

RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
RUN a2enmod rewrite
ADD run.sh /run.sh
RUN chmod +x /*.sh

EXPOSE 80
CMD ["/run.sh"]
