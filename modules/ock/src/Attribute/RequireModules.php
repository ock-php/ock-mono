<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute;

/**
 * Declares that a service depends on a module.
 *
 * @todo This currently does not do anything.
 */
#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
class RequireModules {

  /**
   * Constructor.
   *
   * @param list<string> $modules
   */
  public function __construct(
    public readonly array $modules,
  ) {}

}
