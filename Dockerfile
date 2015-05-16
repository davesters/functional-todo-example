FROM php:5.6.8-cli

RUN apt-get update && apt-get install -y php5-mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app
COPY . /app

CMD ["php -S 0.0.0.0:8080"]