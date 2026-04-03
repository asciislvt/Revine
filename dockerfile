FROM php:apache

# Install ffmpeg, enable apache modules, and install pdo extensions for mysql
RUN apt-get update; \
  apt-get install -y --no-install-recomends ffmpeg; \
  rm -rf /var/lib/apt/lists/*; \
  docker-php-ext-install pdo_mysql; \
  a2enmod headers rewrite

# For hls streaming ;3
COPY ./docker/apache-hls.conf /etc/apache2/conf-available/hls.conf
RUN a2enconf hls; \
  mkdir /data/tmp; \
  mkdir /data/videos
