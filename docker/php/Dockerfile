FROM php:8.2-fpm
EXPOSE 5173
# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    gnupg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Node.js と npm のインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP設定ファイルのコピー
COPY php.ini /usr/local/etc/php/

# 作業ディレクトリ設定
WORKDIR /var/www/src
