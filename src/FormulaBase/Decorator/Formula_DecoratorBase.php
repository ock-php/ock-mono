<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaBase\Decorator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class Formula_DecoratorBase implements FormulaInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   */
  public function __construct(FormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *   The non-optional version.
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }
}
