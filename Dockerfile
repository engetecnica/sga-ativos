FROM php:7.2-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:root ./ ./
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN cp application/config/exemplo.database.php application/config/database.php
RUN echo 0.0.0.0 engetecnica.local localhost >> /etc/hosts
RUN a2enmod rewrite
RUN apachectl restart
RUN echo "Iniciando Container..."
