FROM ubuntu:20.04

ARG ARG_PHP_VERSION
ENV PHP_VERSION=${ARG_PHP_VERSION:-7.4}

ENV APACHE_WORKDIR=/var/www
ENV APACHE_DOCUMENT_ROOT=${APACHE_WORKDIR}/public

ENV BUILD_TIME_DEPS="apt-utils software-properties-common gpg-agent git zip unzip build-essential"
ENV RUN_TIME_DEPS="locales gettext-base curl"

WORKDIR ${APACHE_WORKDIR}

COPY . ${APACHE_WORKDIR}

RUN set -eux \

    # Suppress debian frontend warning
    && echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections \

    # Install generic packages
    && apt-get -qq update && apt-get install --no-install-recommends --assume-yes \
        ${BUILD_TIME_DEPS} ${RUN_TIME_DEPS} \

    # Generate locales
    && locale-gen en_US.UTF-8 && update-locale \

    # Add repositories
    && add-apt-repository --yes --no-update ppa:ondrej/php \
    && add-apt-repository --yes --no-update ppa:ondrej/apache2 \
    && echo "deb https://deb.nodesource.com/node_14.x $(lsb_release -cs) main" | tee -a /etc/apt/sources.list \

    # Install repository signing keys
    && curl -sL "https://deb.nodesource.com/gpgkey/nodesource.gpg.key" | apt-key add - \

    # Install image-related packages
    && apt-get update -qq && apt-get install --no-install-recommends --assume-yes \
        # PHP
        php${PHP_VERSION}-cli php${PHP_VERSION}-curl php${PHP_VERSION}-json \
        php${PHP_VERSION}-mbstring php${PHP_VERSION}-intl php${PHP_VERSION}-mysql php${PHP_VERSION}-odbc \
        php${PHP_VERSION}-zip php${PHP_VERSION}-opcache php${PHP_VERSION}-geoip php${PHP_VERSION}-xml \
        php${PHP_VERSION}-igbinary php${PHP_VERSION}-redis \
        php${PHP_VERSION}-gd php${PHP_VERSION}-imagick \

        # NodeJS + NPM
        nodejs \

        # Apache
        apache2 libapache2-mod-php${PHP_VERSION} libapache2-mod-rpaf \

    # Enable all available extensions for all SAPIs
    && phpenmod -v ${PHP_VERSION} -s ALL $(ls -A /etc/php/${PHP_VERSION}/mods-available/ | cut -d "." -f 1 | tr "\n" " ") \

    # Enable apache2 mods
    && a2enmod \
        actions alias deflate env expires headers http2 mime mime_magic remoteip \
        request rewrite ssl proxy proxy_http proxy_html proxy_connect substitute rpaf \

    # Composer
    && curl -sL https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer \
    && composer self-update --1 \

    # Install global packages
    && npm i -g cross-env webpack \

    # Setup virtual host template
    && /bin/cp -fra /var/www/docker/files/config/apache/000-default.conf /etc/apache2/sites-available/000-default.conf.tpl \

    # Copy over custom Apache configuration files
    && /bin/cp -fra /var/www/docker/files/config/apache/mods-available/rpaf.conf /etc/apache2/mods-available/rpaf.conf \

    # Copy over custom PHP configuration files
    && /bin/cp -fra /var/www/docker/files/config/php/php.ini /etc/php/${PHP_VERSION}/cli/php.ini \
    && /bin/cp -fra /var/www/docker/files/config/php/php.ini /etc/php/${PHP_VERSION}/apache2/php.ini \

    # Setup entrypoint
    && /bin/cp -fr /var/www/docker/files/scripts/entrypoint-docker.sh /usr/local/bin/entrypoint-docker \
    && chmod a+x /usr/local/bin/entrypoint-docker \

    # Install tini init process manager
    && curl -sL https://github.com/krallin/tini/releases/latest/download/tini-amd64 -o /usr/local/bin/tini \
    && chmod +x /usr/local/bin/tini \

    # Install docker-compose wait helper
    && curl -sL https://github.com/ufoscout/docker-compose-wait/releases/latest/download/wait -o /usr/local/bin/docker-compose-wait \
    && chmod a+x /usr/local/bin/docker-compose-wait \

    # Build App
    && composer install \
    && npm install \
    && npm run prod \
    && cp .env.example .env \

    # Cleanup
    && apt-get remove --assume-yes ${BUILD_TIME_DEPS} \
    && apt-get autoremove --assume-yes && apt-get --assume-yes autoclean && apt-get --assume-yes clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configure locales
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

# Allow composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# https://httpd.apache.org/docs/2.4/stopping.html#gracefulstop
STOPSIGNAL WINCH

EXPOSE 80
EXPOSE 443

ENTRYPOINT ["/usr/local/bin/tini", "--", "entrypoint-docker"]
CMD ["run-web"]
