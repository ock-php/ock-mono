#!/bin/bash

set -x

for PACKAGE_DIR in ./packages/*/
do
  cd "$PACKAGE_DIR" || exit 1
  if [ -f phpstan.neon ]; then
    ../../vendor/bin/phpstan
  fi
  cd ../..
done
