FROM php:8.1-fpm

COPY .bashrc /etc/bash.bashrc

# Install linux packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    sudo \
    openssl \
    git \
    zip \
    cron \
    libzip-dev \
    gnupg2 \
    libicu-dev \
    libpng-dev \
    libc-client-dev \
    libkrb5-dev \
    libjpeg-dev \
    bash-completion \
    nano \
    && rm -r /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer --version

# Install node and yarn
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y --no-install-recommends \
    nodejs \
    yarn \
    libpq-dev \
    && rm -r /var/lib/apt/lists/*

# Install and configure php extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_mysql bcmath gd zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && pecl install ast \
    && docker-php-ext-enable ast \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --enable-gd \
    && docker-php-ext-install gd \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets

# Setup git
RUN git config --global alias.st status

# Setup php configs
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
COPY conf.d/*.ini /usr/local/etc/php/conf.d/
COPY conf.d/dev/* /usr/local/etc/php/conf.d/

# Setup cron
COPY cron /etc/cron.d/cron
RUN chmod 0644 /etc/cron.d/cron
RUN crontab /etc/cron.d/cron
RUN touch /var/log/cron.log


CMD php-fpm
