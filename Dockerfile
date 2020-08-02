FROM php:7-apache-buster

RUN apt-get update && \
  apt-get install -y libpq-dev python3 python3-pip libonig-dev && \
  pip3 install unitypack && \
  docker-php-ext-install pdo_pgsql exif mbstring && \
  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
  rm -rf /var/lib/apt/lists/*

COPY . .
RUN chmod +x /var/www/html/Upload/getInfo.py

EXPOSE 80
VOLUME ["/var/www/html/files"]
