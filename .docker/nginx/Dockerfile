FROM nginx:alpine

RUN rm /etc/nginx/conf.d/* -rf

COPY conf/nginx.conf /etc/nginx/nginx.conf
COPY conf/default.conf /etc/nginx/conf.d/default.conf
