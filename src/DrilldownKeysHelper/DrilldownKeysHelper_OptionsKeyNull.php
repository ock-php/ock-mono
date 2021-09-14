<?php

declare(strict_types=1);

namespace Donquixote\Ock\DrilldownKeysHelper;

class DrilldownKeysHelper_OptionsKeyNull implements DrilldownKeysHelperInterface {

  /**
   * @var string
   */
  private $idKey;

  /**
   * @param string $idKey
   */
  public function __construct(string $idKey) {
    $this->idKey = $idKey;
  }

  /**
   * {@inheritdoc}
   */
  public function unpack($conf): array {

    if (!\is_array($conf)) {
      return [NULL, NULL];
    }

    if (!isset($conf[$this->idKey])) {
      return [NULL, NULL];
    }

    if ('' === $id = $conf[$this->idKey]) {
      return [NULL, NULL];
    }

    if (!\is_string($id) && !\is_int($id)) {
      return [NULL, NULL];
    }

    unset($conf[$this->idKey]);
    return [$id, $conf];
  }

  /**
   * {@inheritdoc}
   */
  public function pack($id, $options) {

    $conf = \is_array($options)
      ? $options
      : [];

    $conf[$this->idKey] = $id;

    return $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function getKeys(): array {
    return [$this->idKey, NULL];
  }

  /**
   * {@inheritdoc}
   */
  public function getIdKey(): ?string {
    return $this->idKey;
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
    $idTrail = $parentTrail;
    $idTrail[] = $this->idKey;
    return [$idTrail, $parentTrail];
  }

}
