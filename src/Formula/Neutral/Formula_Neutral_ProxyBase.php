<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Neutral;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

/**
 * A "proxy" formula can be created before the decorated formula exists.
 *
 * This allows e.g. recursive formulas.
 */
abstract class Formula_Neutral_ProxyBase implements Formula_NeutralInterface {

  /**
   * @var FormulaInterface
   */
  private $decorated;

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated
      ?? $this->decorated = $this->doGetDecorated();
  }

  /**
   * @return FormulaInterface
   */
  abstract protected function doGetDecorated(): FormulaInterface;
}
