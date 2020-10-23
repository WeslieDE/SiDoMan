FROM sahrea/webserver

WORKDIR /var/www/html

RUN touch /var/run/docker.sock && chmod 777 /var/run/docker.sock

COPY index.php /var/www/html/index.php

COPY style/ /var/www/html/style/
COPY pages/ /var/www/html/pages/
COPY classen/ /var/www/html/classen/