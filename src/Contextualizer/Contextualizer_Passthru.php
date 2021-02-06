<?php

namespace Donquixote\Cf\Contextualizer;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Generator\GeneratorInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

class Contextualizer_Passthru implements ContextualizerInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   */
  protected function __construct(CfSchemaInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function contextGetSchema(?ContextInterface $context): string {
    // TODO: Implement contextGetSchema() method.
  }

}
