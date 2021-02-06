<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

class TypeToSchema_Buffer implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToSchema\TypeToSchemaInterface $decorated
   */
  public function __construct(TypeToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetSchema(string $type, CfContextInterface $context = NULL): FormulaInterface {

    $k = $type;
    if (NULL !== $context) {
      $k .= '::' . $context->getMachineName();
    }

    return array_key_exists($k, $this->schemas)
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->decorated->typeGetSchema($type, $context);
  }

}
