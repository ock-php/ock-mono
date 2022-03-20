<?php

declare(strict_types=1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactory_StaticMethod;
use Donquixote\Ock\Plugin\NamedTypedPlugin;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OckPluginFormula extends PluginAttributeBase {

  /**
   * {@inheritdoc}
   */
  public function fromClass(\ReflectionClass $reflectionClass): never {
    throw new \RuntimeException('This attribute cannot be used on a class.');
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
    $class = $rtype->getName();
    /** @psalm-suppress TypeDoesNotContainType */
    if ($class === 'self' || $class === 'static') {
      $class = $reflectionMethod->getDeclaringClass()->getName();
    }
    if (!\is_a($class, FormulaInterface::class, true)) {
      throw new \RuntimeException(\sprintf(
        'Unexpected return type on %s.',
        $reflectionMethod->getDeclaringClass()->getName()
        . '::' . $reflectionMethod->getName() . '()'));
    }
    return $this->formulaGetNamedPlugin(
      Formula_FormulaFactory_StaticMethod::fromReflectionMethod($reflectionMethod),
      $reflectionMethod->getDeclaringClass()->getInterfaceNames());
  }

}
