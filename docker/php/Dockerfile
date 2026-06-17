FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    pdo_pgsql \
    pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN touch /var/log/xdebug.log && \
    chmod 777 /var/log/xdebug.log

#COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ARG UID
ARG GID

RUN groupadd -g ${GID} appuser && \
    useradd -u ${UID} -g appuser -m appuser

USER appuser

RUN echo "alias art='php artisan'" >> /home/appuser/.bashrc

WORKDIR /var/www/html

CMD ["php-fpm"]
