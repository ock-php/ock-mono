<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Replacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialBase;

class FormulaToFormula_Iface extends FormulaToAnythingPartialBase {

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
  protected function formulaDoGetObject(FormulaInterface $formula, string $interface, FormulaToAnythingInterface $helper): ?object {
    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface $formula */
    return $this->typeToFormula->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull());

  }

}
