<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Attribute;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Conditionally skip services from the annotated class or method.
 */
interface ServiceConditionAttributeInterface {

  /**
   * Checks whether the class or method should be included in the discovery.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   The container builder that is currently being populated.
   *   This can be used to check container parameters.
   *   E.g. in Drupal, the container parameters contain information about which
   *   modules are installed.
   *
   * @return bool
   *   TRUE to process the annotated class or method.
   *   FALSE to skip it.
   */
  public function check(ContainerBuilder $container): bool;

}
