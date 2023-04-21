FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
  apt-get install -y \
  libzip-dev \
  zip \
  openssl \
  libssl-dev

RUN apt-get install gcc g++ make

RUN apt-get update && \
  apt-get install -y curl gnupg2 && \
  curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Install MariaDB driver
RUN pecl install pdo_mysql && docker-php-ext-enable pdo_mysql

# Enable mod_rewrite
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy the application code
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install

# RUN php artisan migrate:fresh --seed --force

RUN apt-get install -y nodejs

RUN npm install

RUN npm run build

# Create symbolic link
RUN php artisan storage:link

RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
