<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Psr\Container\ContainerInterface;

interface DependencyInjectionArgumentInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return mixed
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public function getArgDefinition(\ReflectionParameter $parameter): mixed;

  /**
   * @param \ReflectionParameter $parameter
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return mixed
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public function paramGetValue(\ReflectionParameter $parameter, ContainerInterface $container): mixed;

}
