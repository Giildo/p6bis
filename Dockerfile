# Development build
FROM php:7.4-apache

ENV WORKDIR "/var/www/html"

RUN apt-get update -y \
  && apt-get install -y \
        libzip-dev \
        zip \
        curl \
        libxml2-dev \
  && apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install zip pdo_pgsql pgsql xml

RUN curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install --lts

RUN a2enmod rewrite && service apache2 restart

COPY . ${WORKDIR}

RUN rm -rf ${WORKDIR}/var && rm -rf ${WORKDIR}/vendor

RUN mkdir -p \
	${WORKDIR}/var/cache \
	${WORKDIR}/var/logs \
	${WORKDIR}/var/sessions \
	&& chown -R www-data ${WORKDIR}/var

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/bin/composer
