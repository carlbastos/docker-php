#!/bin/sh
set -e

@unless ($prod)
if [ "$ENABLE_XDEBUG" == "true" ]; then
    docker-php-ext-enable xdebug >> /dev/null 2>&1

    if [ $? != "0" ]; then
        echo "[ERROR] An error happened enabling xdebug"

        exit 1
    fi
fi
@endunless

# Run as current user
if [ ! -z "$ASUSER" ] && [ "$ASUSER" != "0" ]; then
    usermod -u $ASUSER fwd
fi

if [ "$1" = "bash" ] || [ "$1" = "nginx" ] || [ "$1" = "php-fpm" ] || [ "$1" = "supervisord" ]; then
    exec "$@"
else
    exec su-exec fwd "$@"
fi
