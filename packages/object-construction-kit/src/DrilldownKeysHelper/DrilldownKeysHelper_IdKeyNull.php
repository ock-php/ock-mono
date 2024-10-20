<?php

declare(strict_types=1);

namespace Ock\Ock\DrilldownKeysHelper;

class DrilldownKeysHelper_IdKeyNull implements DrilldownKeysHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function unpack(mixed $conf): array {

    if (!\is_string($conf) && !\is_int($conf)) {
      return [NULL, NULL];
    }

    return [$conf, NULL];
  }

  /**
   * {@inheritdoc}
   */
  public function pack(string|int|null $id, mixed $options): string|int|null {
    return $id;
  }

  /**
   * {@inheritdoc}
   */
  public function getKeys(): array {
    return [NULL, NULL];
  }

  /**
   * {@inheritdoc}
   */
  public function getIdKey(): ?string {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsKey(): ?string {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function buildTrails(array $parentTrail): array {
    return [$parentTrail, $parentTrail];
  }

}
