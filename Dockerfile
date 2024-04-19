FROM php:8.2-cli

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat-traditional libxml2-dev libpq-dev libzip-dev && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    docker-php-ext-enable apcu pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .
RUN composer install
ENV APP_ENV=prod
ENV DATABASE_URl="postgresql://postgres:mysecretpassword@127.0.0.1:5432/yearbook?serverVersion=16&charset=utf8"
CMD php bin/console doctrine:migrations:migrate ;  php bin/console server:run 0.0.0.0:8000

EXPOSE 8000