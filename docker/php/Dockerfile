FROM php:8.3-fpm-alpine

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

COPY . /var/www/html
COPY .env.example .env

RUN apk add supervisor
RUN mkdir /var/log/supervisor
ADD ./docker/supervisor/supervisord.conf /etc/supervisord.conf

RUN apk add composer
RUN apk add $PHPIZE_DEPS

RUN pecl install redis \
    && apk add php-redis \
    && docker-php-ext-enable redis

RUN apk add php-dom \
    php-ctype \
    php-tokenizer \
    php-xml \
    php-simplexml \
    php-xmlwriter \
    php-pdo \
    php-pgsql
RUN composer install

RUN mkdir /var/www/html/storage/logs
RUN touch /var/www/html/storage/logs/app.log
RUN chown -R www-data:www-data /var/www/html

vendor/bin/phinx migrate -e production

CMD ["/bin/sh", "-c", "php-fpm -D && /usr/bin/supervisord -n -c /etc/supervisord.conf"]