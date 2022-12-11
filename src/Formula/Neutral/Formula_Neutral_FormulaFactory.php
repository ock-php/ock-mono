<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Neutral;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Formula\FreeParameters\Formula_FreeParameters;

class Formula_Neutral_FormulaFactory extends Formula_Neutral_ProxyBase {

  /**
   * Constructor.
   *
   * @param callable(mixed...): FormulaInterface $factory
   */
  public function __construct(
    private readonly mixed $factory,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function doGetDecorated(): FormulaInterface {
    try {
      return Formula_FreeParameters::fromFormulaCallback($this->factory);
    }
    catch (\ReflectionException $e) {
      throw new FormulaException('Cannot create formula for factory.', 0, $e);
    }
  }

}
