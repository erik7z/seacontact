FROM nginx:latest

COPY nginx.conf.template /etc/nginx/nginx.conf.template
COPY blocked.conf /etc/nginx/blocked.conf

COPY nginx-entrypoint.sh /
RUN ["chmod", "+x", "/nginx-entrypoint.sh"]
ENTRYPOINT ["/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]