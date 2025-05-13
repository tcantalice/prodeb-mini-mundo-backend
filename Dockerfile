FROM php:8.3-apache

# Install system dependencies
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
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    zip \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    opcache \
    mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy only necessary files for production
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy Apache configuration
COPY ./deploy/apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Set environment variables for production
ENV APP_ENV=production
ENV APP_DEBUG=false

# Start Apache in the foreground
CMD ["apache2-foreground"]
