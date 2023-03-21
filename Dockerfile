FROM php:7.4-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY . /var/www/html/

ENV MYSQL_HOST=db
ENV MYSQL_USER=dbuser
ENV MYSQL_DATABASE=dbname
ENV MYSQL_PASSWORD=dbpass
ENV HOSTNAME=localhost

RUN apt-get update && apt-get install -y default-mysql-client
EXPOSE 80
CMD ["apache2-foreground"]
