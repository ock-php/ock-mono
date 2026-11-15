# Extra tooling for yolobox sessions in this project.
# See .yolobox.toml, and https://github.com/finbarr/yolobox/blob/master/docs/customizing.md

USER root

# PHP 8.3, 8.4, 8.5 from the ondrej/php PPA, with the extensions needed for
# the tests. In PHP 8.5, opcache is built in, there is no separate package.
RUN apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
      software-properties-common unzip \
 && add-apt-repository -y ppa:ondrej/php \
 && apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
      php8.3-cli php8.3-xml php8.3-mbstring php8.3-curl php8.3-gd php8.3-zip php8.3-sqlite3 php8.3-pcov php8.3-opcache \
      php8.4-cli php8.4-xml php8.4-mbstring php8.4-curl php8.4-gd php8.4-zip php8.4-sqlite3 php8.4-pcov php8.4-opcache \
      php8.5-cli php8.5-xml php8.5-mbstring php8.5-curl php8.5-gd php8.5-zip php8.5-sqlite3 php8.5-pcov \
 && update-alternatives --set php /usr/bin/php8.5 \
 && rm -rf /var/lib/apt/lists/*

# Composer.
RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER yolo
