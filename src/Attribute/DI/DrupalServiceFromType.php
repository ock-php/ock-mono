<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Donquixote\Adaptism\DI\ServiceIdHavingInterface;
use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\Util\AttributesUtil;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class DrupalServiceFromType extends ServiceIdBase {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  protected function paramGetServiceId(\ReflectionParameter $parameter): string {
    $reflectionType = $parameter->getType();
    if (!$reflectionType instanceof \ReflectionNamedType || $reflectionType->isBuiltin()) {
      throw new MalformedDeclarationException('No service name specified for parameter.');
    }
    $class = $reflectionType->getName();
    try {
      $reflectionClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      throw new MalformedDeclarationException('Type is unknown.', 0, $e);
    }
    if (NULL !== $serviceId = AttributesUtil::getSingle($reflectionClass, ServiceIdHavingInterface::class)?->getServiceId()) {
      return $serviceId;
    }
    return $class;
  }

}
