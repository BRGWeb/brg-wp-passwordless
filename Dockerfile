FROM wordpress:4.8-php7.1-apache

MAINTAINER BRGWeb contato@brgweb.com.br

RUN apt-get update -y \
    && apt-get upgrade -y

RUN apt-get install \
    ca-certificates \
    git \
    wget \
    --no-install-recommends \
    --no-install-suggests \
    -y

    RUN wget https://getcomposer.org/composer.phar \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

    RUN rm -rf /var/lib/apt/lists/* \
    && apt-get autoremove \
    && apt-get autoclean
