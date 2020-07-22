FROM php:7-apache-buster

RUN apt-get update && \
  apt-get install -y libpq-dev && \
  docker-php-ext-install pdo_pgsql && \
  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
  rm -rf /var/lib/apt/lists/*

COPY . .

EXPOSE 80
VOLUME ["/var/www/html/files"]
