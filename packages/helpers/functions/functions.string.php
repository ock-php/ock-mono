<?php

declare(strict_types=1);

namespace Ock\Helpers;

/**
 * Checks whether a string is a valid identifier.
 *
 * @param string $string
 *
 * @return bool
 */
function is_valid_identifier(string $string): bool {
  return !!\preg_match(
    '@^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$@',
    $string,
  );
}

/**
 * Checks whether a string is a valid qualified (class) name.
 *
 * @param string $string
 *
 * @return bool
 */
function is_valid_qcn(string $string): bool {
  return !!\preg_match(
    '@^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*(\\\\[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)*$@',
    $string,
  );
}

