FROM php:8.2-fpm-alpine3.20

ARG USERID=1000
ARG GROUPID=1000
ARG USERNAME=orderdemo
ARG APPLICATION_VERSION=dev

ENV USERID=${USERID}
ENV GROUPID=${GROUPID}
ENV USERNAME=${USERNAME}
ENV APPLICATION_VERSION=${APPLICATION_VERSION}

COPY --from=composer:2.8.3 /usr/bin/composer /usr/local/bin/composer

# Users + PHP + Symfony
RUN apk add --no-cache busybox-suid sudo bash git libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && addgroup -g ${GROUPID} ${USERNAME} \
    && adduser -D --home /home/${USERNAME}  --ingroup ${USERNAME} --uid ${USERID} ${USERNAME} \
    && adduser ${USERNAME} wheel \
    && touch /etc/sudoers \
    && sed -e 's;^# \(%wheel.*NOPASSWD.*\);\1;g' -i /etc/sudoers \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | sudo -E bash \
    && apk add symfony-cli

# Project PHP extensions
RUN apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql exif gd \
    && docker-php-ext-enable pdo pdo_mysql exif gd

# Install xdebug
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && apk del .phpize-deps

WORKDIR /app
ADD ./ /app

USER ${USERNAME}

# RUN composer install --no-progress --no-interaction \
#     && mkdir -p ./var \
#     && chown -R ${USERNAME}:${USERNAME} /app

RUN git config --global user.email "martin@srsen.sk" \
    && git config --global user.name "Martin Srsen" \
