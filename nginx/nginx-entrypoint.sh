#!/usr/bin/env sh
set -eu

echo "# nginx-entrypoint.sh > replacing nginx config variables >" "$NGINX_HOST" "$SSL_CERT_HOST" "$NGINX_PHP_SOURCE" "$PHP_FPM_HOST"
envsubst '${NGINX_HOST} ${SSL_CERT_HOST} ${NGINX_PHP_SOURCE} ${PHP_FPM_HOST}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
echo "# nginx-entrypoint.sh > completed replacing variables > ready to start >"

exec "$@"
