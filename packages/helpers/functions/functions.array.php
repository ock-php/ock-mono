<?php

declare(strict_types=1);

namespace Ock\Helpers;

/**
 * @template TKey
 * @template TValue
 *
 * @param string $pattern
 * @param array<TKey, TValue> $values
 * @param int $flags
 *
 * @return array<TKey, TValue>
 */
function preg_grep_keys(string $pattern, array $values, int $flags = 0): array {
  $keys = \array_keys($values);
  $filtered_keys = \preg_grep($pattern, $keys, $flags);
  if ($filtered_keys === false) {
    throw new \InvalidArgumentException("Bad pattern '$pattern'.");
  }
  return \array_intersect_key(
    $values,
    \array_fill_keys($filtered_keys, TRUE),
  );
}

/**
 * @template TKey
 * @template TValue
 *
 * @param array<TKey, TValue>|TValue $value
 *
 * @return array<TKey, TValue>
 */
function to_array(mixed $value): array {
  return \is_array($value) ? $value : [$value];
}

/**
 * @template T of object
 *
 * @param array $candidates
 *   Candidate values which may or may not be objects.
 * @param class-string<T> $class
 *   Class or interface to filter by.
 *
 * @return T[]
 *   Filtered values.
 */
function array_filter_instanceof(array $candidates, string $class): array {
  return \array_filter(
    $candidates,
    static fn (mixed $candidate): bool => $candidate instanceof $class,
  );
}
