<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Fallback;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBase;

class Formula_Fallback extends Formula_DecoratorBase implements Formula_FallbackInterface {

  private string $fallbackPhp;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param string $fallback_php
   */
  public function __construct(FormulaInterface $decorated, string $fallback_php) {
    parent::__construct($decorated);
    $this->fallbackPhp = $fallback_php;
  }

  /**
   * {@inheritdoc}
   */
  public function getFallbackPhp(): string {
    return $this->fallbackPhp;
  }

}
