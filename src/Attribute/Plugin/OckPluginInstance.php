<?php

declare(strict_types=1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_Class;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactory_StaticMethod;
use Donquixote\Ock\Plugin\NamedTypedPlugin;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class OckPluginInstance extends PluginAttributeBase {

  /**
   * {@inheritdoc}
   */
  public function fromClass(\ReflectionClass $reflectionClass): NamedTypedPlugin {
    if ($reflectionClass->isAbstract()) {
      throw new \RuntimeException(\sprintf(
        'Class %s must not be abstract.',
        $reflectionClass->getName()));
    }
    $formula = new Formula_ValueFactory_Class($reflectionClass->getName());
    return $this->formulaGetNamedPlugin($formula, $reflectionClass->getInterfaceNames());
  }

  /**
   * {@inheritdoc}
   */
  public function fromMethod(\ReflectionMethod $reflectionMethod): NamedTypedPlugin {
    if (!$reflectionMethod->isStatic()) {
      throw new \RuntimeException(\sprintf(
        'Method %s must be static.',
        $reflectionMethod->getDeclaringClass()->getName()
        . '::' . $reflectionMethod->getName() . '()'));
    }
    $rtype = $reflectionMethod->getReturnType();
    if (!$rtype instanceof \ReflectionNamedType || $rtype->isBuiltin()) {
      throw new \RuntimeException(\sprintf(
        'Missing or unexpected return type on %s.',
        $reflectionMethod->getDeclaringClass()->getName()
        . '::' . $reflectionMethod->getName() . '()'));
    }
    $formula = Formula_ValueFactory_StaticMethod::fromReflectionMethod($reflectionMethod);
    /** @var class-string|'self'|'static' $class */
    $class = $rtype->getName();
    if ($class === 'self' || $class === 'static') {
      $interfaces = $reflectionMethod->getDeclaringClass()->getInterfaceNames();
    }
    else {
      $rclass = new \ReflectionClass($class);
      $interfaces = $rclass->getInterfaceNames();
    }
    return $this->formulaGetNamedPlugin($formula, $interfaces);
  }

}
