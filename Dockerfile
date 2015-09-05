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
  curl -O http://nodejs.org/dist/v0.10.28/node-v0.10.28-linux-x64.tar.gz  && \
  tar -xzf node-v0.10.28-linux-x64.tar.gz && \
  mv node-v0.10.28-linux-x64 node && \
  cd /usr/local/bin && \
  ln -s /opt/node/bin/* . && \
  rm -f /opt/node-v0.10.28-linux-x64.tar.gz
 
# Set the working directory
WORKDIR   /src
EXPOSE 80 8888 3000
CMD ["/bin/bash"]