#!/bin/sh
set -e

mkdir -p /data/tmp /data/media /data/videos
chown -R www-data:www-data /data
chmod -R u+rwX,g+rwX /data

exec apache2-foreground
