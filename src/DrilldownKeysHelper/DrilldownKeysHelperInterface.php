<?php
declare(strict_types=1);

namespace Donquixote\Cf\DrilldownKeysHelper;

interface DrilldownKeysHelperInterface {

  /**
   * @param mixed $conf
   *
   * @return array
   *   Format: [$id, $options]
   */
  public function unpack($conf): array;

  /**
   * @param string|int|null $id
   * @param mixed $options
   *
   * @return array|mixed
   */
  public function pack($id, $options);

  /**
   * @return array
   *   Format: [$idKey, $optionsKey]
   */
  public function getKeys(): array;

  /**
   * @return string|null
   */
  public function getIdKey(): ?string;

  /**
   * @return string|null
   */
  public function getOptionsKey(): ?string;

  /**
   * @param string[] $parentTrail
   *
   * @return string[][]
   *   Format: [$idTrail, $optionsTrail]
   */
  public function buildTrails(array $parentTrail): array;

}
