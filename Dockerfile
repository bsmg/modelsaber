FROM php:7-apache-buster

RUN apt-get update && \
  apt-get install -y libpq-dev python3 python3-pip libonig-dev && \
  pip3 install unitypack && \
  docker-php-ext-install pdo_pgsql exif mbstring && \
  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
  sed -i 's,^error_reporting =.*$,error_reporting = E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED,' "$PHP_INI_DIR/php.ini" && \
  sed -i 's,^post_max_size =.*$,post_max_size = 100M,' "$PHP_INI_DIR/php.ini" && \
  sed -i 's,^upload_max_filesize =.*$,upload_max_filesize = 100M,' "$PHP_INI_DIR/php.ini" && \
  rm -rf /var/lib/apt/lists/*

COPY . .
RUN chmod +x /var/www/html/Upload/getInfo.py

EXPOSE 80
VOLUME ["/var/www/html/files"]
