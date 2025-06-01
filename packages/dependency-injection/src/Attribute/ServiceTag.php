<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

use Symfony\Component\DependencyInjection\Definition;

/**
 * Adds a tag to a service.
 *
 * @see \Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag
 */
#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ServiceTag implements ServiceModifierInterface {

  public function __construct(
    private readonly string $name,
    private readonly array $attributes = [],
  ) {}

  /**
   * {@inheritdoc}
   */
  public function modify(Definition $definition): void {
    $definition->addTag($this->name, $this->attributes);
  }

}
