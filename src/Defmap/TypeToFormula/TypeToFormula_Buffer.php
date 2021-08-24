<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

class TypeToFormula_Buffer implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[][]
   *   Format: $[$type][$orNull] = $formula.
   */
  private $formulas = [];

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface $decorated
   */
  public function __construct(TypeToFormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, bool $or_null): FormulaInterface {
    return ($this->formulas[$type][(int) $or_null])
      ?? ($this->formulas[$type][(int) $or_null] = $this->decorated->typeGetFormula($type, $or_null));
  }

}
