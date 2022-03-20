<?php

declare(strict_types = 1);

namespace Donquixote\Ock\IncarnatorPartial\Registry;

use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class IncarnatorPartialsRegistry_SortDecorator implements IncarnatorPartialsRegistryInterface {

  /**
   * Constructor.
   *
   * @param ReflectionClassesIAInterface $reflectionClassesIA
   * @param ParamToValueInterface $paramToValue
   */
  public function __construct(
    private ReflectionClassesIAInterface $reflectionClassesIA,
    private ParamToValueInterface $paramToValue,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getPartials(): array {
    $partials = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      foreach ($reflectionClass->getAttributes(
        OckIncarnator::class,
        \ReflectionAttribute::IS_INSTANCEOF
      ) as $attribute) {
        /**
         * @var \Donquixote\Ock\Attribute\Incarnator\OckIncarnator $instance
         * @psalm-ignore-var
         */
        $instance = $attribute->newInstance();
        $partials[] = $instance->classGetPartial(
          $reflectionClass,
          $this->paramToValue);
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        foreach ($reflectionMethod->getAttributes(
          OckIncarnator::class,
          \ReflectionAttribute::IS_INSTANCEOF
        ) as $attribute) {
          /**
           * @var \Donquixote\Ock\Attribute\Incarnator\OckIncarnator $instance
           * @psalm-ignore-var
           */
          $instance = $attribute->newInstance();
          $partials[] = $instance->methodGetPartial(
            $reflectionMethod,
            $this->paramToValue);
        }
      }
    }
    return $partials;
  }

}
