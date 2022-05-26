<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Neutral;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Formula_Neutral_ProxyWithReference extends Formula_Neutral_ProxyBase {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface|null $formulaRef
   */
  public function __construct(
    private ?FormulaInterface &$formulaRef = null,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function doGetDecorated(): FormulaInterface {

    if (!$this->formulaRef instanceof FormulaInterface) {
      throw new \RuntimeException("Formula reference is still empty.");
    }

    return $this->formulaRef;
  }

}
