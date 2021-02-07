<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext;
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
    parent::__construct(Formula_IfaceWithContext::class, NULL);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ) {

    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext $formula */

    $formula = $this->typeToFormula->typeGetFormula(
      $formula->getInterface(),
      $formula->getContext());

    if (NULL === $formula) {
      return NULL;
    }

    return $helper->formula($formula, $interface);
  }
}
