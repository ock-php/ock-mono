<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class IdToFormula_Recursive implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   *   Decorated object for first level lookup.
   */
  public function __construct(IdToFormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  public function idGetFormula(string $id): ?FormulaInterface {
    $id_parts = explode('/', $id);
    $idToFormula = $this->decorated;
    foreach ($id_parts as $id_part) {
      $formula = $idToFormula->idGetFormula($id_part);
    }
  }

}
