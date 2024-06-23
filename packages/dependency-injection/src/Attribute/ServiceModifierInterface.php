<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

use Symfony\Component\DependencyInjection\Definition;

interface ServiceModifierInterface {

  /**
   * Modifies a service definition.
   *
   * @param \Symfony\Component\DependencyInjection\Definition $definition
   */
  public function modify(Definition $definition): void;

}
