FROM phpdockerio/php:8.1-fpm AS base
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
      php8.1-mysql \
      php8.1-intl \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


FROM base AS deploy
COPY --chown=www-data:www-data ./composer.json /application/
COPY --chown=www-data:www-data ./composer.lock /application/
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-progress --no-scripts --prefer-dist
COPY --chown=www-data:www-data ./src /application/src
COPY --chown=www-data:www-data ./plugins /application/plugins
COPY --chown=www-data:www-data ./config /application/config
COPY --chown=www-data:www-data ./config/app.production.php /application/config/app.php
COPY --chown=www-data:www-data ./templates /application/templates
COPY --chown=www-data:www-data ./webroot/index.php /application/webroot/index.php
RUN mkdir -p /application/tmp && chown -R www-data:www-data /application/tmp
RUN mkdir -p /application/logs && chown -R www-data:www-data /application/logs
