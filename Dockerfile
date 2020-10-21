FROM sahrea/webserver

RUN composer require docker-php/docker-php

COPY index.php /var/www/html/index.php

COPY style/ /var/www/html/style/
COPY pages/ /var/www/html/pages/
COPY data/ /var/www/html/data/
COPY classen/ /var/www/html/classen/