#!/bin/sh

Fail() {
  echo "ERROR: $@" 1>&2
  exit 1
}

which realpath >/dev/null || Fail "realpath not found"
which php      >/dev/null || Fail "php not found"
which composer      >/dev/null || Fail "composer not found"

cd "$(realpath "$(dirname "$0")"/..)"

composer install --prefer-dist --no-interaction
composer dumpautoload -o

php bin/console cache:clear --no-warmup --env=dev
php bin/console cache:clear --no-warmup --env=prod
php bin/console cache:warmup --env=prod