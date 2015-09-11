FROM ubuntu:14.04

RUN apt-get update
RUN apt-get install -y nginx zip curl

RUN echo "daemon off;" >> /etc/nginx/nginx.conf
RUN curl -o /usr/share/nginx/html/1-master.zip -L https://coding.net/u/sscode/p/wogame/git/archive/master
RUN cd /usr/share/nginx/html/ && unzip sscode-wogame-master.zip && mv sscode-wogame-master/* . && rm -rf sscode-wogame-master sscode-wogame-master.zip

EXPOSE 80

CMD ["/usr/sbin/nginx", "-c", "/etc/nginx/nginx.conf"]
