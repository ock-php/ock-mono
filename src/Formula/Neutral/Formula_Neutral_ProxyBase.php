<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Neutral;

use Donquixote\Ock\Core\Formula\FormulaInterface;

/**
 * A "proxy" formula can be created before the decorated formula exists.
 *
 * This allows e.g. recursive formulas.
 */
abstract class Formula_Neutral_ProxyBase implements Formula_NeutralInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated
      ??= $this->doGetDecorated();
  }

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  abstract protected function doGetDecorated(): FormulaInterface;

}
