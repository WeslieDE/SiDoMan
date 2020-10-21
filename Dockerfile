FROM sahrea/webserver

RUN composer require docker-php/docker-php

COPY index.php /var/www/html/index.php
COPY .htaccess /var/www/html/.htaccess

COPY style/ /var/www/html/style/
COPY seiten/ /var/www/html/seiten/
COPY plugins/ /var/www/html/plugins/
COPY daten/ /var/www/html/daten/
COPY classen/ /var/www/html/classen/