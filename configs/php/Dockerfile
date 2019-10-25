FROM alpine:3.9

ARG BUILD_PAKAGES="\
git \
openssl \
curl \
php7 \
php7-fpm \
php7-phar \
php7-ctype \
php7-curl \
php7-json \
php7-iconv \
php7-mbstring \
php7-mcrypt \
php7-openssl \
php7-pdo_sqlite \
php7-tokenizer \
php7-zip \
php7-session \
bash \
mc \
"

ARG BUILD_TMP_PAKAGES=""

RUN set -ex && \
    apk --update add --no-cache $BUILD_PAKAGES && \
    rm -rf /var/cache/apk/*
    #ln -sf /dev/stdout /var/log/php7/error.log

RUN set -x \
    && addgroup -g 82 -S www-data \
    && adduser -u 82 -D -S -G www-data www-data 999

RUN set -eux; \
    [ ! -d /var/www/html ]; \
    mkdir -p /var/www/html; \
    chown www-data:www-data /var/www/html; \
    chmod 777 /var/www/html

COPY ./conf/php-fpm.conf /etc/php7/php-fpm.conf
COPY ./conf/www.conf /etc/php7/php-fpm.d/www.conf

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
RUN set -ex && \
    curl -sS https://getcomposer.org/installer | php &&\
    chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

EXPOSE 9000

CMD ["php-fpm7"]