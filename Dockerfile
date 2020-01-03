FROM alpine:3.11
MAINTAINER Jefta Dirksen <jeftadirksen@gmail.com>

RUN apk update && apk upgrade && apk add apache2 php7 php7-apache2 php7-json bash

WORKDIR /var/www/localhost/htdocs
COPY src/ /var/www/localhost/htdocs/
RUN rm /var/www/localhost/htdocs/index.html
RUN mkdir /otpl
RUN chown apache:apache /otpl

EXPOSE 80

ENTRYPOINT ["/usr/sbin/httpd"]
CMD ["-D", "FOREGROUND"]
