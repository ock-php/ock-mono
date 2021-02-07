<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;

class IdToFormula_FilterDecorator implements IdToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @var \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  private $condition;

  /**
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $condition
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
