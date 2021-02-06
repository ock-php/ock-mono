<?php

namespace Donquixote\OCUI\Contextualizer;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Generator\GeneratorInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

class Contextualizer_Passthru implements ContextualizerInterface {

  /**
   * @var \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
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
