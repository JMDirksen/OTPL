FROM php:7.3-apache

COPY src/ /var/www/html/

#RUN cp /var/www/html/otpl.config.example.php otpl.config.php

RUN echo "<?php\n \
    \$email      = 'admin@domain.com';\n \
    \$expireDays = 7;\n \
    \$title      = 'One Time Password Link';\n \
    \$logo       = 'logo.png';\n \
    \$cssFile    = 'otpl.css';\n \
    \$jsonFile   = '/otpl/db.json';\n" > /var/www/html/otpl.config.php


RUN mkdir /otpl
RUN chown www-data:www-data /otpl

EXPOSE 80
