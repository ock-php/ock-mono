<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Neutral_ProxyWithReference extends Formula_Neutral_ProxyBase {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  private $schemaRef;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface|null $schemaRef
   */
  public function __construct(FormulaInterface &$schemaRef = NULL) {
    $this->schemaRef =& $schemaRef;
  }

  /**
   * {@inheritdoc}
   */
  public function doGetDecorated(): FormulaInterface {

    if (!$this->schemaRef instanceof FormulaInterface) {
      throw new \RuntimeException("Schema reference is still empty.");
    }

    return $this->schemaRef;
  }
}
