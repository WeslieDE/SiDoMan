FROM sahrea/webserver

WORKDIR /var/www/html

COPY index.php /var/www/html/index.php

COPY style/ /var/www/html/style/
COPY pages/ /var/www/html/pages/
COPY data/ /var/www/html/data/
COPY classen/ /var/www/html/classen/