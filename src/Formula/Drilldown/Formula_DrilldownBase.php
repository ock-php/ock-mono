<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

abstract class Formula_DrilldownBase implements Formula_DrilldownInterface {

  /**
   * @var false
   */
  private $orNull;

  /**
   * Constructor.
   *
   * @param false $orNull
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
