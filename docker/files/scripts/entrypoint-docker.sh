#!/usr/bin/env bash

# Exit immediately if a *pipeline* returns a non-zero status
set -euo pipefail

# Script command to be executed
CMD=${1:-run-web}

# Process some known arguments ...
case $CMD in
    run-web)
        echo "Starting web server..."

        # Apache gets grumpy about PID files pre-existing
        rm -f /usr/local/apache2/logs/httpd.pid

        # Read apache2 env vars
        export APACHE_CONFDIR=/etc/apache2/
        source /etc/apache2/envvars

        # Cleanup and compile apache default virtual host
        rm -rf /etc/apache2/sites-{available,enabled}/*.conf
        envsubst < /etc/apache2/sites-available/000-default.conf.tpl > /etc/apache2/sites-available/000-default.conf

        # Enable default virtual host
        a2ensite 000-default

        # Fix permissions
        chgrp -R www-data storage bootstrap/cache
        chmod -R ug+rwx storage bootstrap/cache

        # Migrate database
        WAIT_HOSTS_TIMEOUT="300" WAIT_AFTER_HOSTS="5" WAIT_SLEEP_INTERVAL="0.5" WAIT_HOSTS="database:3306" docker-compose-wait && php artisan migrate:fresh --seed

        # Run apache2 in foreground
        /usr/sbin/apachectl -DFOREGROUND
        ;;
esac

# Otherwise just run the specified command
exec "$@"
