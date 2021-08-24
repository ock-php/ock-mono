<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class IdToFormula_Class implements IdToFormulaInterface {

  /**
   * @var string
   */
  private $class;

  /**
   * @param string $class
   */
  public function __construct(string $class) {
    $this->class = $class;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    $candidate = new $this->class($id);

    if (!$candidate instanceof FormulaInterface) {
      return NULL;
    }

    return $candidate;
  }
}
