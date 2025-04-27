<?php

declare(strict_types=1);

namespace Ock\Adaptism\Inspector;

use Ock\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Ock\Adaptism\AdaptismPackage;
use Ock\Adaptism\Attribute\SelfAdapter;
use Ock\Adaptism\SpecificAdapter\SpecificAdapter_SelfMethod;
use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\DependencyInjection\Attribute\ServiceTag;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

/**
 * Finds adapter methods where the `$this` object is the adaptee.
 *
 * @see \Ock\Adaptism\Attribute\SelfAdapter
 *
 * @template-implements FactoryInspectorInterface<string, \Ock\Adaptism\AdapterDefinition\AdapterDefinitionInterface>
 */
#[ServiceTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class FactoryInspector_SelfAdapterAttribute implements FactoryInspectorInterface {

  use AdapterFactoryInspectorTrait;

  /**
   * Constructor.
   *
   * @param \Ock\Egg\ParamToEgg\ParamToEggInterface $paramToEgg
   */
  public function __construct(
    protected readonly ParamToEggInterface $paramToEgg,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    // Attribute should not be used on classes, only on methods.
    if (!$reflector instanceof \ReflectionMethod
      // Disregard the method, if it is declared in a parent class.
      || $reflector->isInherited()
    ) {
      return;
    }
    $attribute = AttributesUtil::getSingle($reflector, SelfAdapter::class);
    if ($attribute === NULL) {
      return;
    }
    // The method must not be static.
    if ($reflector->isStatic()) {
      // The message must specify the place in the code that needs to be fixed.
      throw new MalformedDeclarationException(\sprintf(
        'Usage of attribute %s on %s is not allowed.',
        get_class($attribute),
        $reflector->getDebugName(),
      ));
    }
    $parameters = $reflector->getParameters();
    $hasResultTypeParameter = $this->extractHasResultTypeParameter($parameters);
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    $moreArgEggs = $this->buildArgEggs($parameters);
    $adapterEgg = SpecificAdapter_SelfMethod::egg(
      $reflector->class,
      $reflector->name,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgEggs,
    );
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflector, true);
    $specifity = $attribute->specifity
      ?? \count($reflector->getDeclaringClass()->getInterfaceNames());
    $definition = new AdapterDefinition_Simple(
      $reflector->class,
      $returnClass,
      $specifity,
      $adapterEgg,
    );
    yield $reflector->getFullName() => $definition;
  }

}
