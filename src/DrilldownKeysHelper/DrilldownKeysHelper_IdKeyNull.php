<?php
declare(strict_types=1);

namespace Donquixote\ObCK\DrilldownKeysHelper;

class DrilldownKeysHelper_IdKeyNull implements DrilldownKeysHelperInterface  {

  /**
   * {@inheritdoc}
   */
  public function unpack($conf): array {

    if (!\is_string($conf) && !\is_int($conf)) {
      return [NULL, NULL];
    }

    return [$conf, NULL];
  }

  /**
   * {@inheritdoc}
   */
  public function pack($id, $options) {

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
