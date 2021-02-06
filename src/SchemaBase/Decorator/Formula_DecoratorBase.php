<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase\Decorator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_DecoratorBase implements FormulaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
   */
  public function __construct(FormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *   The non-optional version.
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }
}
