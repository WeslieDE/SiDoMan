FROM sahrea/webserver

WORKDIR /var/www/html

RUN apt-get update && apt-get -y install apt-transport-https ca-certificates curl gnupg-agent software-properties-common
RUN curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add -
RUN add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
RUN apt-get update && apt-get -y install docker-ce-cli socat 

RUN touch /var/run/docker.sock && chmod 777 /var/run/docker.sock

COPY index.php /var/www/html/index.php
COPY api.php /var/www/html/api.php

COPY style/ /var/www/html/style/
COPY pages/ /var/www/html/pages/
COPY classen/ /var/www/html/classen/