FROM php:8.4

RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    librabbitmq-dev \
    cron \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install amqp \
    && docker-php-ext-enable amqp

COPY . /var/www/html

WORKDIR /var/www/html

RUN echo "* * * * * root php /var/www/html/artisan schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab

RUN touch /var/log/cron.log

CMD ["cron", "-f"]
