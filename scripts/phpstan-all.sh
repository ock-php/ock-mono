#!/bin/bash

# Runs phpstan for each package that has a phpstan.neon.
# Continues after a failure, and exits with non-zero status if any failed.

set -e

# Work from the repository root, wherever the script is called from.
cd "$(dirname "$0")/.."

FAILED_PACKAGES=()

for PACKAGE_DIR in packages/*/
do
  PACKAGE_DIR="${PACKAGE_DIR%/}"
  if [ ! -f "$PACKAGE_DIR/phpstan.neon" ]; then
    continue
  fi
  echo
  echo "=== PhpStan: $PACKAGE_DIR ==="
  (cd "$PACKAGE_DIR" && ../../vendor/bin/phpstan) || FAILED_PACKAGES+=("$PACKAGE_DIR")
done

echo
if [ "${#FAILED_PACKAGES[@]}" -gt 0 ]; then
  echo "PhpStan failed for: ${FAILED_PACKAGES[*]}"
  exit 1
fi
echo "PhpStan passed for all packages."
