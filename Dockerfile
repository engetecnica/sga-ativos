FROM php:7.2-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:root ./ ./
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli 
RUN rm application/config/database.php > /dev/null
RUN cp application/config/exemplo.database.php application/config/database.php
RUN chmod -R 775 assets/uploads
RUN chown -R root:www-data assets/uploads
RUN a2enmod rewrite 
RUN apachectl restart 
RUN echo "Development Deploy Successfully!"