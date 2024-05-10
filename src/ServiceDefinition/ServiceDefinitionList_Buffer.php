<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinition;

/**
 * Decorator that buffers the result, to avoid repeated discovery.
 *
 * A similar decorator could be written for persistent caching.
 *
 * @template KeyedById as bool
 *
 * @template-extends \Donquixote\DID\ServiceDefinition\ServiceDefinitionListInterface<KeyedById>
 */
class ServiceDefinitionList_Buffer implements ServiceDefinitionListInterface {

  private ?array $buffer = null;

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ServiceDefinition\ServiceDefinitionListInterface<KeyedById> $decorated
   */
  public function __construct(
    private readonly ServiceDefinitionListInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    return $this->buffer ??= $this->decorated->getDefinitions();
  }

}
