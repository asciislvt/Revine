#!/bin/sh
set -e

mkdir -p /data/tmp /data/media /data/videos
chown -R www-data:www-data /data
chmod -R u+rwX,g+rwX /data

# creating folder for php restful api
# mkdir -p /var/www/api
chown -R www-data:www-data /var/www/api
chmod -R 777 /var/www/api

exec apache2-foreground
