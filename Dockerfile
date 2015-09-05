FROM ubuntu:14.04 
MAINTAINER Jechiy <jechiy@gmail.com>
RUN   \
 apt-get update 
RUN   \
 apt-get install -y curl
# Install Redis
RUN   \
  apt-get -y -qq install python redis-server
 
# Install Node
RUN   \
  cd /opt && \
  curl -O http://dn-downfile.qbox.me/node-v0.10.28-linux-x64.zip  && \
  unzip node-v0.10.28-linux-x64.zip && \
  mv node-v0.10.28-linux-x64 node && \
  cd /usr/local/bin && \
  ln -s /opt/node/bin/* . && \
  rm -rf node-v0.10.28-linux-x64.zip
 
# Set the working directory
WORKDIR   /src
EXPOSE 80 8888 3000
CMD ["/bin/bash"]