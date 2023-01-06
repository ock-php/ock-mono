<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinition;

/**
 * Decorator that uses service id as array key, and removes duplicate entries.
 *
 * @template-extends \Donquixote\DID\ServiceDefinition\ServiceDefinitionListInterface<true>
 */
class ServiceDefinitionList_RekeyAndDedupe implements ServiceDefinitionListInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ServiceDefinition\ServiceDefinitionListInterface $decorated
   */
  public function __construct(
    private readonly ServiceDefinitionListInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    $definitions = [];
    foreach ($this->decorated->getDefinitions() as $definition) {
      // Only keep the first instance.
      $definitions[$definition->id] ??= $definition;
    }
    return $definitions;
  }

}
