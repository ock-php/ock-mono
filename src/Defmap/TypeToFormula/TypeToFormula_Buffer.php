<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

class TypeToFormula_Buffer implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[][]
   *   Format: $[$type][$orNull] = $formula.
   */
  private $formulas = [];

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface $decorated
   */
  public function __construct(TypeToFormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, bool $orNull): FormulaInterface {
    return ($this->formulas[$type][(int) $orNull])
      ?? ($this->formulas[$type][(int) $orNull] = $this->decorated->typeGetFormula($type, $orNull));
  }

}
