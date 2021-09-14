<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Iface;

class Formula_Iface implements Formula_IfaceInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var bool
   */
  private $orNull;

  /**
   * Constructor.
   *
   * @param string $interface
   * @param bool $orNull
   */
  public function __construct(string $interface, bool $orNull = FALSE) {
    $this->interface = $interface;
    $this->orNull = $orNull;
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): bool {
    return $this->orNull;
  }

  /**
   * {@inheritdoc}
   */
  public function getInterface(): string {
    return $this->interface;
  }

}
