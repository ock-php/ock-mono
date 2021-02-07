<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Neutral_ProxyWithReference extends Formula_Neutral_ProxyBase {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  private $formulaRef;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface|null $formulaRef
   */
  public function __construct(FormulaInterface &$formulaRef = NULL) {
    $this->formulaRef =& $formulaRef;
  }

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
