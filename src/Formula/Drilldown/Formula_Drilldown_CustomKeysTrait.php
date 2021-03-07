<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

trait Formula_Drilldown_CustomKeysTrait {

  /**
   * @var string
   */
  private $idKey = 'id';

  /**
   * @var string
   */
  private $optionsKey = 'options';

  /**
   * @param string $idKey
   * @param string $optionsKey
   *
   * @return static
   */
  public function withKeys(string $idKey, string $optionsKey): self {
    $clone = clone $this;
    $clone->idKey = $idKey;
    $clone->optionsKey = $optionsKey;
    return $clone;
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
    return $this->optionsKey;
  }

}
