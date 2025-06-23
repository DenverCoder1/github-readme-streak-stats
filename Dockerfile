# Use official PHP with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions in one layer
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    inkscape \
    fonts-dejavu-core \
    curl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
COPY src/ ./src/
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Configure Apache to serve from src/ directory and pass environment variables
RUN a2enmod rewrite headers && \
    echo 'ServerTokens Prod\n\
ServerSignature Off\n\
PassEnv TOKEN\n\
<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/src\n\
    <Directory /var/www/html/src>\n\
        Options -Indexes\n\
        AllowOverride None\n\
        Require all granted\n\
        Header always set Access-Control-Allow-Origin "*"\n\
        Header always set Content-Type "image/svg+xml" "expr=%{REQUEST_URI} =~ m#\\.svg$#i"\n\
        Header always set Content-Security-Policy "default-src 'none'; style-src 'unsafe-inline'; img-src data:;" "expr=%{REQUEST_URI} =~ m#\\.svg$#i"\n\
        Header always set Referrer-Policy "no-referrer-when-downgrade"\n\
        Header always set X-Content-Type-Options "nosniff"\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set secure permissions
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/demo/ || exit 1

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]