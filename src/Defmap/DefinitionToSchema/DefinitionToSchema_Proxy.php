<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class DefinitionToSchema_Proxy implements DefinitionToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface|null
   */
  private $instance;

  /**
   * @var callable
   */
  private $factory;

  /**
   * @param callable $factory
   */
  public function __construct(callable $factory) {
    $this->factory = $factory;
  }

  /**
   * {@inheritdoc}
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL): CfSchemaInterface {

    if (NULL === $this->instance) {
      $this->instance = \call_user_func($this->factory);
    }

    return $this->instance->definitionGetSchema($definition, $context);
  }

}
