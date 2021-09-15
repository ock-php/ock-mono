<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Drilldown;

abstract class Formula_DrilldownBase implements Formula_DrilldownInterface {

  /**
   * @var false
   */
  private $orNull;

  /**
   * Constructor.
   *
   * @param bool $orNull
   */
  public function __construct($orNull = FALSE) {
    $this->orNull = $orNull;
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): bool {
    return $this->orNull;
  }

}
