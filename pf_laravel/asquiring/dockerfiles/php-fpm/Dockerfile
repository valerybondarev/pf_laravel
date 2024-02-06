FROM php:8.1-fpm

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev librabbitmq-dev libssh-dev && \
    pecl install apcu xdebug amqp && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql soap && \
    docker-php-ext-enable apcu pdo_pgsql sodium xdebug amqp && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -L https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v3.7.0/php-cs-fixer.phar -o php-cs-fixer && \
	chmod a+x php-cs-fixer && \
	mv php-cs-fixer /usr/local/bin/php-cs-fixer

ADD ./php.ini /usr/local/etc/php/php.ini

ADD fixer.sh /usr/local/bin/fixer.sh
RUN chmod a+x /usr/local/bin/fixer.sh

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer i -o ; wait-for-it db:5432 -- bin/console doctrine:migrations:migrate ;  php-fpm 

EXPOSE 9000
