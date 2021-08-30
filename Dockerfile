FROM php:7.2-apache
USER root
WORKDIR /var/www/html/
COPY --chown=root:root ./ ./
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN cp application/config/heroku.database.php application/config/database.php
RUN chmod -R 775 assets/uploads
RUN chown -R root:www-data assets/uploads
RUN a2enmod rewrite 
RUN apachectl restart
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN composer.phar install
RUN echo "Production Deploy Successfully!"