<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;

class IdToFormula_FilterDecorator implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @var \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  private $condition;

  /**
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $condition
   *
   * @todo There should be a narrower interface for $condition parameter.
   */
  public function __construct(IdToFormulaInterface $decorated, Formula_IdInterface $condition) {
    $this->idToFormula = $decorated;
    $this->condition = $condition;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    if (!$this->condition->idIsKnown($id)) {
      return NULL;
    }

    return $this->idToFormula->idGetFormula($id);
  }
}
