#!/bin/sh

Fail() {
  echo "ERROR: $@" 1>&2
  exit 1
}

which realpath >/dev/null || Fail "realpath not found"
which php      >/dev/null || Fail "php not found"
which composer      >/dev/null || Fail "composer not found"

cd "$(realpath "$(dirname "$0")"/..)"

export APP_ENV=prod

composer install --prefer-dist --no-interaction
composer dump-autoload --optimize --no-dev --classmap-authoritative

php bin/console cache:clear --no-warmup
php bin/console cache:warmup