FROM php:8.3-fpm-alpine

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

COPY . /var/www/html
COPY .env.example .env

RUN apk add supervisor
RUN mkdir /var/log/supervisor
ADD ./docker/supervisor/supervisord.conf /etc/supervisord.conf

RUN apk add --no-cache composer \
    $PHPIZE_DEPS

RUN pecl install redis \
    && apk add --no-cache php-redis \
    && docker-php-ext-enable redis

RUN set -ex \
	&& apk --no-cache add postgresql-libs postgresql-dev \
	&& docker-php-ext-install pgsql pdo_pgsql \
	&& apk del postgresql-dev

RUN apk add php-dom \
    php-ctype \
    php-tokenizer \
    php-xml \
    php-simplexml \
    php-xmlwriter \
    php-pdo \
    php-pgsql
RUN composer install

RUN mkdir -p /var/www/html/storage/logs
RUN touch /var/www/html/storage/logs/app.log
RUN chown -R www-data:www-data /var/www/html

#RUN vendor/bin/phinx migrate -e development

CMD ["/bin/sh", "-c", "php-fpm -D && /usr/bin/supervisord -n -c /etc/supervisord.conf"]