FROM nginx:alpine
WORKDIR "/application"
COPY ./.docker/webserver/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./webroot /application/webroot
EXPOSE 80
