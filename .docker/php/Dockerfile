FROM php:8.3-fpm

# Updating repositories and installing dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    wget \
    git \
    msmtp \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    imagemagick libmagickwand-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd iconv mbstring mysqli pdo_mysql zip \
    && pecl install imagick xdebug \
    && docker-php-ext-enable imagick xdebug \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installing Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Installing Node.js and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs
RUN npm --version && node --version

# Setting access rights
RUN chown -R www-data:www-data /var/www/html

# Working directory
WORKDIR /var/www/html

# Creating alias for Symfony
RUN echo 'alias sf=\"php app/console\"' >> ~/.bashrc
