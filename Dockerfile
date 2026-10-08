FROM php:8.4-cli

WORKDIR /app

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . .

EXPOSE 8000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8000} -t public"]