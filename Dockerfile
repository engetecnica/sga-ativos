FROM php:7.4-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:www-data ./ ./
RUN usermod -a root -G www-data
RUN usermod -a www-data -G root
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli
RUN a2enmod rewrite
RUN apachectl restart
RUN echo "Development Deploy Successfully!"