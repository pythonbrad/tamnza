# We create the user
echo "CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE;"\
    "CREATE USER '$MYSQL_USERNAME'@'$MYSQL_ROOT_HOST' IDENTIFIED BY '$MYSQL_PASSWORD';"\
    "GRANT ALL PRIVILEGES ON * . * TO '$MYSQL_USERNAME'@'$MYSQL_ROOT_HOST';"\
    "FLUSH PRIVILEGES;"\
    | mysql

# We insert data
mysql -D "$MYSQL_DATABASE"  < ./tamnza/classroom/classroom.sql
