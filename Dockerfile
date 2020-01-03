FROM alpine:3.11
MAINTAINER Jefta Dirksen <jeftadirksen@gmail.com>

RUN apk update && apk upgrade && apk add apache2 php7 php7-apache2 php7-json

RUN mkdir /otpl
RUN chown apache:apache /otpl
VOLUME /otpl

WORKDIR /var/www/localhost/htdocs
COPY src/ .
COPY src/otpl.config.example.php ./otpl.config.php
RUN rm index.html

ENV EMAIL=admin@domain.com
ENV EXPIRE_DAYS=7
ENV PAGE_TITLE="One Time Password Link"
ENV LOGO=logo.png
ENV CSS=otpl.css
ENV JSON=/otpl/otpl.json

EXPOSE 80

ENTRYPOINT ["/usr/sbin/httpd"]
CMD ["-D", "FOREGROUND"]
