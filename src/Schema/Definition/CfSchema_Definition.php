<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Definition;

use Donquixote\Cf\Context\CfContextInterface;

class CfSchema_Definition implements CfSchema_DefinitionInterface {

  /**
   * @var array
   */
  private $definition;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array $definition
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
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
