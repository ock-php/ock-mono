<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Replacer;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialBase;

class FormulaToFormula_Iface extends FormulaToAnythingPartialBase {

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
    parent::__construct(Formula_IfaceInterface::class, FormulaInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaDoGetObject(FormulaInterface $formula, string $interface, FormulaToAnythingInterface $helper): ?object {
    /** @var \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $formula */
    return $this->typeToFormula->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull());

  }

}
