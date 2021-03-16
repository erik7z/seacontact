#!/usr/bin/env sh
set -eu

echo "#### nginx-entrypoint.sh >>>> replacing nginx config variables >>>>"

envsubst '${NGINX_HOST} ${SSL_CERT_HOST}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

exec "$@"