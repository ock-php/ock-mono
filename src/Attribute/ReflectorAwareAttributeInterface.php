<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute;

interface ReflectorAwareAttributeInterface {

  /**
   * Sets the reflector where the attribute was found.
   *
   * @param \Reflector $reflector
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public function setReflector(\Reflector $reflector): void;

}
