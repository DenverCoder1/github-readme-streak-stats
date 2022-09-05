FROM ubuntu:latest
ENV TZ=Asia/Kolkata \
    DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y &&apt upgrade -y && apt install -y nginx php php-curl php-fpm curl php-intl php-xml php-mbstring openssl libmaxminddb-dev zip unzip php-zip supervisor
RUN apt clean -y && apt autoclean -y && apt autoremove -y
ADD default /etc/nginx/sites-available/default
ADD . /var/www/
ADD ./src /var/www/html
RUN chmod -R 777 /var/www/html
RUN chmod -R 777 /var/www
RUN chmod -R 777 /var/www
RUN chmod -R 777 /var/www/html/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
WORKDIR /var/www
RUN composer update && composer install
EXPOSE 80:80
#CMD [ "/usr/sbin/nginx", "-g", "daemon off;" ]
ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD initial.sh /usr/bin/initial
RUN chmod +x /usr/bin/initial

ENTRYPOINT [ "initial" ]