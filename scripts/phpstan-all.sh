#!/bin/bash

# Runs phpstan for each package that has a phpstan.neon.
# Continues after a failure, and exits with non-zero status if any failed.
# Prints a summary with the number of errors per package.

set -e
set -o pipefail

# Work from the repository root, wherever the script is called from.
cd "$(dirname "$0")/.."

LOG="$(mktemp)"
trap 'rm -f "$LOG"' EXIT

SUMMARY=()
FAILED=0

for PACKAGE_DIR in packages/*/
do
  PACKAGE_DIR="${PACKAGE_DIR%/}"
  if [ ! -f "$PACKAGE_DIR/phpstan.neon" ]; then
    continue
  fi
  echo
  echo "=== PhpStan: $PACKAGE_DIR ==="
  # Output is piped, so force colors to keep the usual look.
  if (cd "$PACKAGE_DIR" && ../../vendor/bin/phpstan --ansi) 2>&1 | tee "$LOG"; then
    RESULT="OK"
  else
    FAILED=1
    # The table output ends with "Found N error(s)".
    COUNT="$(sed 's/\x1b\[[0-9;]*m//g' "$LOG" | grep -oE 'Found [0-9]+ errors?' | grep -oE '[0-9]+' | tail -n 1 || true)"
    if [ -n "$COUNT" ]; then
      RESULT="$COUNT errors"
    else
      RESULT="failed (no error count found)"
    fi
  fi
  SUMMARY+=("$(printf '%-40s %s' "$PACKAGE_DIR" "$RESULT")")
done

echo
echo "=== PhpStan summary ==="
printf '%s\n' "${SUMMARY[@]}"

exit "$FAILED"
