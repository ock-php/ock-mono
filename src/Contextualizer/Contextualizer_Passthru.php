<?php

namespace Donquixote\OCUI\Contextualizer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Generator\GeneratorInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

class Contextualizer_Passthru implements ContextualizerInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   */
  protected function __construct(FormulaInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function contextGetSchema(?ContextInterface $context): string {
    // TODO: Implement contextGetSchema() method.
  }

}
