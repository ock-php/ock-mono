<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class TypeToSchema_Buffer implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Donquixote\Cf\Defmap\TypeToSchema\TypeToSchemaInterface $decorated
   */
  public function __construct(TypeToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetSchema(string $type, CfContextInterface $context = NULL): CfSchemaInterface {

    $k = $type;
    if (NULL !== $context) {
      $k .= '::' . $context->getMachineName();
    }

    return array_key_exists($k, $this->schemas)
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->decorated->typeGetSchema($type, $context);
  }

}
