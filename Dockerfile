FROM ubuntu:14.04

RUN apt-get update
RUN apt-get install -y nginx zip curl

RUN echo "daemon off;" >> /etc/nginx/nginx.conf
RUN curl -o /usr/share/nginx/html/1-master.zip -L https://codeload.github.com/jechiy/1/zip/master
RUN cd /usr/share/nginx/html/ && unzip 1-master.zip && mv 1-master/* . && rm -rf 1-master 1-master.zip

EXPOSE 80

CMD ["/usr/sbin/nginx", "-c", "/etc/nginx/nginx.conf"]
