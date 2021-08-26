<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class TypeToFormula_Buffer implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[][][]
   *   Format: $[spl_object_hash($helper)][$type][$orNull] = $formula.
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
    // @todo Get a real cache id from the helper object.
    $hash = spl_object_hash($helper);
    return ($this->formulas[$hash][$type][(int) $or_null])
      ?? ($this->formulas[$hash][$type][(int) $or_null] = $this->decorated->typeGetFormula($type, $or_null));
  }

}
