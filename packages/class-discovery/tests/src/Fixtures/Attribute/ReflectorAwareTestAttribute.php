<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Tests\Fixtures\Attribute;

use Ock\ClassDiscovery\Attribute\ReflectorAwareAttributeInterface;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_ALL)]
class ReflectorAwareTestAttribute implements ReflectorAwareAttributeInterface {

  public ?\Reflector $reflector = NULL;

  #[\Override]
  public function setReflector(\Reflector $reflector): void {
    $this->reflector = $reflector;
  }

}
