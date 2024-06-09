<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Parametric;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface PlaceholderInterface {

  /**
   * Tells if the resolve() method needs arguments.
   *
   * @return bool
   */
  public function needsArguments(): bool;

  /**
   * @param array $arguments
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *
   * @return mixed|$this
   *   The resolved value to use in a service definition.
   *   If it cannot be resolved, $this is returned.
   */
  public function resolve(array $arguments, ContainerBuilder $container): mixed;

}
