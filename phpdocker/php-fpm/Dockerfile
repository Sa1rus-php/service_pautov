FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.2-bcmath \ 
        php8.2-bz2 \
        php8.2-enchant \
        php8.2-gd \
        php8.2-http \
        php8.2-imagick \
        php8.2-intl \
        php8.2-mcrypt \
        php8.2-mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
