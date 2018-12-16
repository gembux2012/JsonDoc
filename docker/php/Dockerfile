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
RUN d docker-php-ext-install mcrypt
RUN d docker-php-ext-install mbstring
RUN d docker-php-ext-install mysqli
RUN d docker-php-ext-install pdo_mysql
RUN d docker-php-ext-install gd


         #&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
         #&& docker-php-ext-install -j$(nproc) gd

RUN pecl install -o -f xdebug \
    && rm -rf /tmp/pear

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./php.ini /usr/local/etc/php/
COPY ./www.conf /usr/local/etc/php/

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && sh /install-composer.sh \
    && rm /install-composer.sh

RUN usermod -u 1000 www-data

VOLUME /root/.composer
WORKDIR /app

EXPOSE 9000
CMD ["php-fpm"]
