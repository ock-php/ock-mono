#!/bin/bash

set -ex

FAILED=0

for PACKAGE_DIR in ./packages/*/
do
  cd "$PACKAGE_DIR" || exit 1
  if [ -f phpstan.neon ]; then
    mkdir -p vendor
    ../../vendor/bin/phpstan || FAILED=1
  fi
  cd ../..
done

exit "$FAILED"
