FROM php:7.3-fpm

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install -y \
        git \
        libpq-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libxpm-dev \
        libmcrypt-dev \
        cron \
        tzdata

RUN cp /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime
RUN echo "America/Sao_Paulo" >  /etc/timezone

RUN apt-get install -y libpq-dev libzip-dev \
    && docker-php-ext-configure mysqli -with-mysqli=mysqlnd \
    && docker-php-ext-configure pdo_mysql -with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ \
                                   --with-png-dir=/usr/include/ \
                                   --with-xpm-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-install zip

# Install xdebug
RUN pecl install xdebug-2.8.0beta1 \
    && docker-php-ext-enable xdebug

RUN curl https://getcomposer.org/composer.phar --output /usr/bin/composer
RUN chmod +x /usr/bin/composer

COPY config/php.ini /usr/local/etc/php/conf.d/
WORKDIR /app
COPY config/crontab /etc/cron.d/kaf-cron
RUN crontab /etc/cron.d/kaf-cron
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
