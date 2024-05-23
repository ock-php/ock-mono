<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
class ServiceTags {

  /**
   * Constructor.
   *
   * @param string[] $tags
   */
  public function __construct(
    public readonly array $tags,
  ) {}

}
