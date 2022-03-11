apt-get update -yq
apt-get install -yq mariadb-server wget libonig-dev curl
# Install PHP extensions
docker-php-ext-install mbstring pdo_mysql
wget -q -O phpunit https://phar.phpunit.de/phpunit-9.phar
