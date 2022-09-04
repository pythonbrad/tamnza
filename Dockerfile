FROM php:8.1-alpine

WORKDIR /app

RUN docker-php-ext-install pdo_mysql

COPY tamnza /app

CMD ["php", "-S", "0.0.0.0:80"]