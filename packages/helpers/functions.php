<?php

declare(strict_types=1);

namespace Ock\Helpers;

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
