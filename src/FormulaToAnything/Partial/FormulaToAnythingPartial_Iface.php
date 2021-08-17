<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;

/**
 * @STA
 */
class FormulaToAnythingPartial_Iface extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface
   */
  private $typeToFormula;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface $typeToFormula
   */
  public function __construct(TypeToFormulaInterface $typeToFormula) {
    $this->typeToFormula = $typeToFormula;
    parent::__construct(Formula_IfaceInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula): ?FormulaInterface {
    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface $formula */
    return $this->typeToFormula->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull());
  }

}
