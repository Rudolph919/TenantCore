#!/bin/sh
set -e

# Ensure storage and bootstrap/cache exist and are writable (fixes bind-mount permission issues)
# Required for Blade compilation (tempnam) and Laravel runtime
mkdir -p /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec "$@"
