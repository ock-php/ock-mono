<?php
declare(strict_types=1);

namespace Donquixote\Ock\FormulaBase\Decorator;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Formula_DecoratorBase implements FormulaInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   */
  public function __construct(FormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *   The non-optional version.
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }
}
