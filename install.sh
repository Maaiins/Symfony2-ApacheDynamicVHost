#!/usr/bin/env bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )


# Directories
mkdir ${DIR}/app/Resources/vHosts
chown -R www-data:www-data ${DIR}/app/Resources/vHosts/


# Curl
apt-get update -y
apt-get install curl -y


# Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
composer install


# Init Doctrine
php app/console doctrine:generate:entities AppBundle
php app/console doctrine:schema:update --force


# Apache
cp /var/www/ApacheDynamicVHost/dynamic-host.conf /etc/apache2/sites-available/000-default.conf


# Cron
crontab -l | { cat; echo "*/1 * * * * ${DIR}/app/Resources/cron/cron.sh"; } | crontab -


service apache2 restart