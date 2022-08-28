FROM nginx:1.14

COPY nginx.conf.template /etc/nginx/nginx.conf.template
COPY blocked.conf /etc/nginx/blocked.conf

# getting run args, setting them as envs and writing to console
ARG NGINX_HOST
ARG NGINX_PHP_SOURCE
ARG SSL_CERT_HOST
ARG PHP_FPM_HOST

ENV NGINX_HOST $NGINX_HOST
ENV NGINX_PHP_SOURCE $NGINX_PHP_SOURCE
ENV SSL_CERT_HOST $SSL_CERT_HOST
ENV PHP_FPM_HOST $PHP_FPM_HOST

RUN echo $NGINX_HOST $NGINX_PHP_SOURCE $SSL_CERT_HOST $PHP_FPM_HOST

COPY nginx-entrypoint.sh /
RUN ["chmod", "+x", "/nginx-entrypoint.sh"]
ENTRYPOINT ["/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
