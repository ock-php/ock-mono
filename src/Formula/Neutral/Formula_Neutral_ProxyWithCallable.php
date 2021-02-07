<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Neutral_ProxyWithCallable extends Formula_Neutral_ProxyBase {

  /**
   * @var callable
   */
  private $formulaCallback;

  /**
   * @param callable $formulaCallback
   */
  public function __construct(callable $formulaCallback) {
    $this->formulaCallback = $formulaCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function doGetDecorated(): FormulaInterface {

    $formula = \call_user_func($this->formulaCallback);

    if (!$formula instanceof FormulaInterface) {
      throw new \RuntimeException("Callback did not return a formula.");
    }

    return $formula;
  }
}
