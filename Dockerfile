FROM php:7.2-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:root ./ ./
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN docker-php-ext-install gd
RUN docker-php-ext-enable gd
RUN cp application/config/heroku.database.php application/config/database.php
RUN chmod -R 775 assets/uploads
RUN chown -R root:www-data assets/uploads
RUN a2enmod rewrite 
RUN apachectl restart
RUN echo "Production Deploy Successfully!"