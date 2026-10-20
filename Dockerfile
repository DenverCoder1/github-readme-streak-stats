# PHP 8.3 + Apache container for Vercel (must listen on $PORT)
FROM php:8.3-apache@sha256:6be4ef702b2dd05352f7e5fe14667696a4ad091c9d2ad9083becbee4300dc3b1

# System dependencies + intl extension
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

# Composer
COPY --from=composer/composer:latest-bin@sha256:c9bda63056674836406cacfbbdd8ef770fb4692ac419c967034225213c64e11b /composer /usr/bin/composer

WORKDIR /var/www/html

# Install dependencies
COPY composer.json composer.lock ./
COPY api/ ./api/
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Apache: serve api/ as document root; "/" -> index.php, "/demo/" -> demo/index.php
RUN a2enmod rewrite headers && cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
ServerTokens Prod
ServerSignature Off
PassEnv TOKEN
PassEnv WHITELIST
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/api
    DirectoryIndex index.php
    <Directory /var/www/html/api>
        Options -Indexes
        AllowOverride None
        Require all granted
        Header always set Access-Control-Allow-Origin "*"
        Header always set Content-Type "image/svg+xml" "expr=%{REQUEST_URI} =~ m#\.svg$#i"
        Header always set Content-Security-Policy "default-src 'none'; style-src 'unsafe-inline'; img-src data:;" "expr=%{REQUEST_URI} =~ m#\.svg$#i"
        Header always set Referrer-Policy "no-referrer-when-downgrade"
        Header always set X-Content-Type-Options "nosniff"
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Vercel routes traffic to $PORT; rewrite the listen port at container start
CMD ["bash", "-c", "sed -ri \"s/^Listen .*/Listen ${PORT:-80}/\" /etc/apache2/ports.conf && apache2-foreground"]
