ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-fpm-alpine

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

ARG APCU_VERSION=5.1.21

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
		postgresql-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
		pdo \
		pdo_pgsql \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
		redis \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
		redis \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

# php.ini: Use the dev configuration
# https://hub.docker.com/_/php
RUN ln -s "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./conf.d/php.ini-development $PHP_INI_DIR/conf.d/symfony.ini

# Install Composer from composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/srv/app/vendor/bin"

# Configure permissions
ARG UID
RUN adduser -D -u $UID user
USER user

WORKDIR /srv/app