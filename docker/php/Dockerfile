FROM php:8.3-fpm-alpine

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

COPY . /var/www/html
COPY .env.example .env
RUN chown -R www-data:www-data /var/www/html

RUN apk add composer
RUN apk add $PHPIZE_DEPS

RUN pecl install redis \
    && apk add php-redis \
    && docker-php-ext-enable redis

RUN apk add php-dom \
    && apk add php-ctype \
    && apk add php-tokenizer \
    && apk add php-xml \
    && apk add php-simplexml \
    && apk add php-xmlwriter
RUN composer install
RUN chown -R www-data:www-data vendor

RUN apk add multirun
#RUN touch /var/www/html/cli/worker.sh
#RUN echo "/usr/local/bin/php /var/www/html/cli/QueueWorker.php" > /var/www/html/cli/worker.sh
#RUN chown www-data:www-data /var/www/html/cli/worker.sh
#RUN chmod a+x /var/www/html/cli/worker.sh

WORKDIR /var/www/html/cli
#CMD ["multirun", "\"/usr/local/bin/php /var/www/html/cli/QueueWorker.php\""]