<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_Iface extends FormulaToAnythingPartialBase {

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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  protected function schemaDoGetObject(
    FormulaInterface $schema,
    string $interface,
    FormulaToAnythingInterface $helper
  ) {

    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext $schema */

    $schema = $this->typeToFormula->typeGetFormula(
      $schema->getInterface(),
      $schema->getContext());

    if (NULL === $schema) {
      return NULL;
    }

    return $helper->schema($schema, $interface);
  }
}
