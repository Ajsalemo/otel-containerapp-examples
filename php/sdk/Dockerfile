FROM composer:2.8.2 as build
COPY . /app/

FROM php:8.3.12-apache-bookworm as production

COPY --from=build /app /var/www/html
COPY --from=build /usr/bin/composer /usr/local/bin/composer
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

ENV PHP_INI_SCAN_DIR /usr/local/etc/php/conf.d:/var/www/html/otel

# Change permissions to read/write for storage or else it'll fail to write out logs due to lack of permissions
# Change ownership to www-data for Apache
RUN chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite && \
    apt-get update -y && \
    apt-get install -y libzip-dev git && \
    pecl install opentelemetry && \
    composer require open-telemetry/sdk open-telemetry/opentelemetry-auto-slim monolog/monolog open-telemetry/exporter-otlp open-telemetry/opentelemetry-logger-monolog && \
    composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction && \
    service apache2 restart

EXPOSE 80