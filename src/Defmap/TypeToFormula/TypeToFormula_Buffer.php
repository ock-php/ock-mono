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
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface $decorated
   */
  public function __construct(TypeToFormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, CfContextInterface $context = NULL): FormulaInterface {

    $k = $type;
    if (NULL !== $context) {
      $k .= '::' . $context->getMachineName();
    }

    return array_key_exists($k, $this->schemas)
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->decorated->typeGetFormula($type, $context);
  }

}
