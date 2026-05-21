FROM php:8.3-fpm-alpine

# Argumentos de build
ARG UID=1000
ARG GID=1000

# Instalar dependências do sistema
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    curl \
    bash \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        bcmath \
        mbstring \
        intl \
        opcache \
    && apk del --no-cache ${PHPIZE_DEPS} \
    && rm -rf /var/cache/apk/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuração do PHP para produção
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configurações personalizadas do PHP
RUN echo "memory_limit = 256M" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "upload_max_filesize = 20M" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "post_max_size = 20M" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "max_execution_time = 300" >> $PHP_INI_DIR/conf.d/custom.ini \
    && echo "opcache.enable = 1" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.memory_consumption = 128" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer = 8" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files = 10000" >> $PHP_INI_DIR/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps = 0" >> $PHP_INI_DIR/conf.d/opcache.ini

# Criar usuário não-root
RUN addgroup -g ${GID} -S laravel \
    && adduser -u ${UID} -S laravel -G laravel

# Configurar PHP-FPM para rodar como usuário laravel
RUN sed -i 's/user = www-data/user = laravel/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = laravel/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/listen = 127.0.0.1:9000/listen = 9000/g' /usr/local/etc/php-fpm.d/www.conf

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar aplicação (se existir)
COPY --chown=laravel:laravel ./app /var/www/html

# Ajustar permissões
RUN chown -R laravel:laravel /var/www/html \
    && chmod -R 755 /var/www/html

# Expor porta PHP-FPM
EXPOSE 9000

# Mudar para usuário não-root
USER laravel

# Comando de inicialização
CMD ["php-fpm"]
