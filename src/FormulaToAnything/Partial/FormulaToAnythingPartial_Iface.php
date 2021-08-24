<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @STA
 */
class FormulaToAnythingPartial_Iface extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface
   */
  private $typeToFormula;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface $typeToFormula
   */
  public function __construct(TypeToFormulaInterface $typeToFormula) {
    $this->typeToFormula = $typeToFormula;
    parent::__construct(Formula_IfaceInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface {
    /** @var \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $formula */
    return $this->typeToFormula->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull());
  }

}
