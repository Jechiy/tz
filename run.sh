#!/bin/bash

chown www-data:www-data /app -R

export MYSQL_DB_HOST=${MYSQL_DB_HOST:-${MYSQL_PORT_3306_TCP_ADDR}}
export MYSQL_DB_PORT=${MYSQL_DB_PORT:-${MYSQL_PORT_3306_TCP_PORT}}

echo "=> Using the following MySQL configuration:"
echo "========================================================================"
echo "      Database Host Address:  $MYSQL_DB_HOST"
echo "      Database Port number:   $MYSQL_DB_PORT"
echo "      Database Name:          $MYSQL_INSTANCE_NAME"
echo "      Database Username:      $MYSQL_USERNAME"
echo "      Database Password:      $MYSQL_PASSWORD"
echo "========================================================================"

touch /.mysql_db_created
source /etc/apache2/envvars
exec apache2 -D FOREGROUND