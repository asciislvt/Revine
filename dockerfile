FROM php:apache

COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Install ffmpeg, enable apache modules, and install pdo extensions for mysql
RUN apt-get update; \
  apt-get install -y --no-install-recommends ffmpeg; \
  rm -rf /var/lib/apt/lists/*; \
  docker-php-ext-install pdo_mysql; \
  a2enmod headers rewrite;

COPY ./docker/apache-hls.conf /etc/apache2/conf-available/hls.conf
RUN a2enconf hls

# File size limit for uploads
COPY ./docker/file_size.ini /usr/local/etc/php/conf.d/custom.ini
