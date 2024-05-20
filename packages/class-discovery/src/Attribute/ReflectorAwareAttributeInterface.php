<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Attribute;

interface ReflectorAwareAttributeInterface {

  /**
   * Sets the reflector where the attribute was found.
   *
   * @param \Reflector $reflector
   *   The place where the attribute was found.
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function setReflector(\Reflector $reflector): void;

}
