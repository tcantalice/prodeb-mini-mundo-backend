FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    libxml2-dev \
    git \
    libicu-dev \
    libonig-dev \
    libxslt1-dev \
    zlib1g-dev \
    libpq-dev \
    sqlite3 \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    zip \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    opcache \
    mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . .
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

RUN a2enmod rewrite headers

COPY ./deploy/apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./deploy/entrypoint .

EXPOSE 80

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_TZ=America/Bahia

ENTRYPOINT entrypoint
