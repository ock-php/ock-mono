<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Definition;

use Donquixote\ObCK\Context\CfContextInterface;

class Formula_Definition implements Formula_DefinitionInterface {

  /**
   * @var array
   */
  private $definition;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array $definition
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   */
  public function __construct(array $definition, CfContextInterface $context = NULL) {
    $this->definition = $definition;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition(): array {
    return $this->definition;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }
}
