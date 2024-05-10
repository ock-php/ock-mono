<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Inspector;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdaptismPackage;
use Donquixote\Adaptism\Attribute\SelfAdapter;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_SelfMethod;
use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Finds adapter methods where the `$this` object is the adaptee.
 *
 * @see \Donquixote\Adaptism\Attribute\SelfAdapter
 *
 * @template-implements FactoryInspectorInterface<\Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface>
 */
#[AutoconfigureTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class FactoryInspector_SelfAdapterAttribute implements FactoryInspectorInterface {

  use AdapterFactoryInspectorTrait;

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   */
  public function __construct(
    protected readonly ParamToCTVInterface $paramToCTV,
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
    $moreArgCTVs = $this->buildArgCTVs($parameters);
    $adapterCTV = SpecificAdapter_SelfMethod::ctv(
      $reflector->class,
      $reflector->name,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgCTVs,
    );
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflector, true);
    $specifity = $attribute->specifity
      ?? \count($reflector->getDeclaringClass()->getInterfaceNames());
    $definition = new AdapterDefinition_Simple(
      $reflector->class,
      $returnClass,
      $specifity,
      $adapterCTV,
    );
    yield $reflector->getFullName() => $definition;
  }

}
