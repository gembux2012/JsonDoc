FROM php:7.2-fpm

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        libmcrypt-dev \
        wget \
        git \
            --no-install-recommends

#-j$(nproc) iconv mcrypt mbstring mysqli pdo_mysql zip \

RUN  docker-php-ext-install -j$(nproc)  iconv
RUN  docker-php-ext-install -j$(nproc) mbstring
RUN  docker-php-ext-install -j$(nproc) mysqli
RUN  docker-php-ext-install -j$(nproc) pdo_mysql
RUN  docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) gd


RUN pecl install -o -f xdebug \
    && rm -rf /tmp/pear

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./php.ini /usr/local/etc/php/
COPY ./www.conf /usr/local/etc/php/
COPY ./hosts /etc/



VOLUME /root/.composer
WORKDIR /app

EXPOSE 9000
CMD ["php-fpm"]
