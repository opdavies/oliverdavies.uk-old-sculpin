FROM php:7.3-apache-stretch AS apache

FROM apache AS composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
