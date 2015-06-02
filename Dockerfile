FROM php:5.6-apache

RUN apt-get update && apt-get install -y php5-mysql php5-dev curl php5-curl
RUN docker-php-ext-install mysql mysqli pdo_mysql
RUN a2enmod rewrite
ADD apache_default /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html