FROM php:7.2-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:root ./ ./
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN rm application/config/database.php
RUN cp application/config/heroku.database.php application/config/database.php
RUN chmod -R 775 assets/uploads
RUN chown -R root:www-data assets/uploads
#RUN sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
RUN a2enmod rewrite 
RUN apachectl restart
RUN echo "Production Deploy Successfully!"