<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Reflection;

use Ock\Helpers\Util\MessageUtil;

/**
 * Enhanced parameter reflector.
 */
class ParameterReflection extends \ReflectionParameter implements AttributesHavingReflectionInterface {

  use AttributesHavingReflectionTrait;

  /**
   * Gets the parameter type class name, if it is unique.
   *
   * @return class-string|null
   *   A class name, if the declared return type is a single class name.
   *   NULL, if the declared type is not a single class name.
   */
  public function getParamClassName(): ?string {
    $type = $this->getType();
    if (!$type instanceof \ReflectionNamedType
      || $type->isBuiltin()
    ) {
      return null;
    }
    $name = $type->getName();
    if ($name === 'self' || $name === 'static') {
      $name = $this->getDeclaringClass()?->name
        // With current php versions, the exception below should be impossible.
        ?? throw new \RuntimeException(sprintf(
          'Unexpected %s parameter type on %s.',
          $name,
          MessageUtil::formatReflector($this),
        ));
    }
    return $name;
  }


}
