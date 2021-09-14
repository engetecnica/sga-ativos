FROM php:7.4-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:www-data ./ ./
RUN usermod -a root -G www-data
RUN usermod -a www-data -G root
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli
RUN rm application/config/database.php > /dev/null
RUN cp application/config/exemplo.database.php application/config/database.php
RUN cp application/config/exemplo.config.php application/config/config.php
RUN chmod -R 755 assets/uploads
RUN a2enmod rewrite
RUN apachectl restart 
RUN echo "Development Deploy Successfully!"