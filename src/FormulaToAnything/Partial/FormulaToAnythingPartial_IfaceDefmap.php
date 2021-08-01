<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_IfaceDefmap extends FormulaToAnythingPartialBase {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface
   */
  private $typeToFormula;

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface $typeToFormula
   */
  public function __construct(TypeToFormulaInterface $typeToFormula) {
    $this->typeToFormula = $typeToFormula;
    parent::__construct(Formula_IfaceInterface::class, NULL);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {

    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface $formula */

    $formula = $this->typeToFormula->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull());

    if (NULL === $formula) {
      return NULL;
    }

    return $helper->formula($formula, $interface);
  }
}
