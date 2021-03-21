FROM alpine:3.12 as base

ARG proxy=''
ARG no_proxy=''

ENV HTTP_PROXY=$proxy
ENV HTTPS_PROXY=$proxy
ENV NO_PROXY=$no_proxy

RUN apk add --upgrade --no-cache \
    apache2 \
    bash \
    curl \
    composer \
    make \
    php7 \
    php7-apache2 \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-intl \
    php7-opcache \
    php7-pdo \
    php7-pdo_mysql \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-sodium \
    php7-xml \
    php7-zip \
    php7-gd \
    php7-fileinfo \
    vim

# Define timezone
RUN apk add --upgrade --no-cache tzdata && \
    cp /usr/share/zoneinfo/Europe/Paris /etc/localtime && \
    echo "Europe/Paris" > /etc/timezone && \
    echo "date.timezone=Europe/Paris" >> /etc/php7/php.ini && \
    apk del tzdata

# Add Naitways CA certificate
RUN apk add --upgrade --no-cache ca-certificates
COPY config/ntw-infra-ca.crt /usr/local/share/ca-certificates/ntw-infra-ca.crt
RUN update-ca-certificates

# Clean apk tmp files
RUN rm -rf /var/cache/apk/* /tmp/*

COPY bin/* /usr/local/bin/
RUN chmod +x /usr/local/bin/*

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
